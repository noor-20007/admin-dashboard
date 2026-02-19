<?php

namespace App\FileManager\Http\Controllers;

use App\FileManager\Storage\StorageInterface;
use App\FileManager\Security\UserPathResolver;
use App\FileManager\Services\ArchiveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\JsonResponse;

/**
 * Download Controller - Handles file downloads with streaming
 * 
 * This controller provides download functionality:
 * 1. Single file download (streamed)
 * 2. Multiple files/folders download (as ZIP archive)
 * 
 * Design Decision: Use streaming for all downloads to avoid memory issues
 * with large files. Files are never loaded entirely into memory.
 * 
 * Performance: Can handle multi-GB files without memory exhaustion
 */
class DownloadController extends Controller
{
    public function __construct(
        private StorageInterface $storage,
        private UserPathResolver $pathResolver,
        private ArchiveService $archiveService
    ) {}

    /**
     * View/Preview file in browser
     * 
     * GET /filemanager/view?path=documents/report.pdf
     */
    public function view(Request $request)
    {
        try {
            $relativePath = $request->input('path');
            
            if (!$relativePath) {
                abort(400, 'Path is required');
            }
            
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            // Check if file exists
            if (!$this->storage->exists($userPath)) {
                abort(404, 'File not found');
            }
            
            // Check if it's a file (not directory)
            if ($this->storage->isDirectory($userPath)) {
                abort(400, 'Cannot view directory');
            }
            
            // Get file metadata
            $filename = basename($relativePath);
            $mimeType = $this->storage->getMimeType($userPath);
            $absolutePath = $this->pathResolver->getAbsolutePath($relativePath);
            
            // Return file for inline viewing (not download)
            return response()->file($absolutePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            \Log::error('View error', ['error' => $e->getMessage()]);
            abort(500, 'View failed: ' . $e->getMessage());
        }
    }

    /**
     * Download single file (streamed)
     * 
     * GET /filemanager/download?path=documents/report.pdf
     * 
     * Response: File stream with appropriate headers
     */
    public function download(Request $request)
    {
        try {
            $relativePath = $request->input('path');
            
            if (!$relativePath) {
                abort(400, 'Path is required');
            }
            
            \Log::info('Download request', ['relativePath' => $relativePath]);
            
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            \Log::info('Resolved path', ['userPath' => $userPath]);
            
            // Check if file exists
            if (!$this->storage->exists($userPath)) {
                \Log::error('File not found', ['userPath' => $userPath]);
                abort(404, 'File not found');
            }
            
            // Check if it's a file (not directory)
            if ($this->storage->isDirectory($userPath)) {
                abort(400, 'Cannot download directory. Use batch download instead.');
            }
            
            // Get file metadata
            $filename = basename($relativePath);
            $mimeType = $this->storage->getMimeType($userPath);
            $fileSize = $this->storage->getSize($userPath);
            
            \Log::info('Starting download', [
                'filename' => $filename,
                'size' => $fileSize,
                'mime' => $mimeType
            ]);
            
            // Get the actual file path
            $absolutePath = $this->pathResolver->getAbsolutePath($relativePath);
            
            \Log::info('Absolute path', ['absolutePath' => $absolutePath]);
            
            // Use Laravel's download response
            return response()->download($absolutePath, $filename, [
                'Content-Type' => $mimeType,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Download error', ['error' => $e->getMessage()]);
            abort(500, 'Download failed: ' . $e->getMessage());
        }
    }

    /**
     * Download multiple files/folders as ZIP archive
     * 
     * POST /api/filemanager/download/batch
     * Body: {
     *   "paths": ["documents/file1.pdf", "images/photo.jpg", "projects"],
     *   "archiveName": "my-files.zip"
     * }
     * 
     * Response: ZIP file stream
     */
    public function downloadBatch(Request $request): StreamedResponse|JsonResponse
    {
        $request->validate([
            'paths' => 'required|array|min:1',
            'paths.*' => 'required|string',
            'archiveName' => 'nullable|string',
        ]);

        try {
            $relativePaths = $request->input('paths');
            $archiveName = $request->input('archiveName', 'download.zip');
            
            // Resolve all paths to user's directory
            $userPaths = [];
            foreach ($relativePaths as $relativePath) {
                $userPath = $this->pathResolver->resolveUserPath($relativePath);
                
                // Verify path exists
                if (!$this->storage->exists($userPath)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Path not found: {$relativePath}",
                    ], 404);
                }
                
                $userPaths[] = $userPath;
            }
            
            // Create ZIP archive
            $archivePath = $this->archiveService->createArchive($userPaths, $archiveName);
            
            // Stream archive to client and clean up after
            return response()->stream(
                function () use ($archivePath) {
                    $stream = fopen($archivePath, 'rb');
                    
                    while (!feof($stream)) {
                        echo fread($stream, 8192);
                        flush();
                    }
                    
                    fclose($stream);
                    
                    // Clean up archive file after streaming
                    $this->archiveService->cleanupArchive($archivePath);
                },
                200,
                [
                    'Content-Type' => 'application/zip',
                    'Content-Disposition' => 'attachment; filename="' . $archiveName . '"',
                    'Content-Length' => filesize($archivePath),
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get download info (file size, estimated archive size)
     * 
     * POST /api/filemanager/download/info
     * Body: {"paths": ["documents/file1.pdf", "images"]}
     * 
     * Response: {
     *   "success": true,
     *   "totalSize": 52428800,
     *   "estimatedArchiveSize": 39321600,
     *   "files": [...]
     * }
     */
    public function downloadInfo(Request $request): JsonResponse
    {
        $request->validate([
            'paths' => 'required|array|min:1',
            'paths.*' => 'required|string',
        ]);

        try {
            $relativePaths = $request->input('paths');
            $userPaths = [];
            $files = [];
            $totalSize = 0;
            
            foreach ($relativePaths as $relativePath) {
                $userPath = $this->pathResolver->resolveUserPath($relativePath);
                
                if (!$this->storage->exists($userPath)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Path not found: {$relativePath}",
                    ], 404);
                }
                
                $size = $this->storage->getSize($userPath);
                $totalSize += $size;
                
                $files[] = [
                    'path' => $relativePath,
                    'type' => $this->storage->isDirectory($userPath) ? 'dir' : 'file',
                    'size' => $size,
                ];
                
                $userPaths[] = $userPath;
            }
            
            // Estimate archive size
            $estimatedArchiveSize = $this->archiveService->estimateArchiveSize($userPaths);
            
            return response()->json([
                'success' => true,
                'totalSize' => $totalSize,
                'estimatedArchiveSize' => $estimatedArchiveSize,
                'files' => $files,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
