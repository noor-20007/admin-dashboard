<?php

namespace App\FileManager\Http\Controllers;

use App\FileManager\Storage\StorageInterface;
use App\FileManager\Security\UserPathResolver;
use App\FileManager\Security\UploadValidator;
use App\FileManager\Services\ChunkManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Upload Controller - Handles file uploads with chunking support
 * 
 * This controller supports two upload modes:
 * 1. Simple upload: For small files (single request)
 * 2. Chunked upload: For large files (multiple requests)
 * 
 * Chunked Upload Flow:
 * 1. Client splits file into chunks
 * 2. Client uploads each chunk with metadata (uploadId, chunkIndex, totalChunks)
 * 3. Server stores chunks temporarily
 * 4. After last chunk, server merges chunks into final file
 * 5. Server cleans up temporary chunks
 * 
 * Benefits:
 * - Supports files larger than server upload limit
 * - Resumable uploads (retry failed chunks)
 * - Better progress tracking
 * - Memory efficient (streams)
 */
class UploadController extends Controller
{
    public function __construct(
        private StorageInterface $storage,
        private UserPathResolver $pathResolver,
        private UploadValidator $uploadValidator,
        private ChunkManager $chunkManager
    ) {}

    /**
     * Simple file upload (for small files)
     * 
     * POST /api/filemanager/upload
     * Body: multipart/form-data
     *   - file: File to upload
     *   - path: Destination directory (optional, defaults to root)
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file',
            'path' => 'nullable|string',
        ]);

        try {
            $file = $request->file('file');
            $destinationPath = $request->input('path', '') ?? '';
            
            // Validate file
            $this->uploadValidator->validate($file);
            
            // Build full file path
            $filename = $file->getClientOriginalName();
            $relativePath = $destinationPath ? "{$destinationPath}/{$filename}" : $filename;
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            // Upload using stream (memory efficient)
            $stream = fopen($file->getRealPath(), 'r');
            $this->storage->writeStream($userPath, $stream);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'path' => $relativePath,
                'size' => $file->getSize(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Initialize chunked upload
     * 
     * POST /api/filemanager/upload/init
     * Body: {
     *   "filename": "large-video.mp4",
     *   "filesize": 524288000,
     *   "path": "videos"
     * }
     * 
     * Response: {
     *   "success": true,
     *   "uploadId": "upload_abc123",
     *   "chunkSize": 1048576
     * }
     */
    public function initChunkedUpload(Request $request): JsonResponse
    {
        $request->validate([
            'filename' => 'required|string',
            'filesize' => 'required|integer|min:1',
            'path' => 'nullable|string',
        ]);

        try {
            $filesize = $request->input('filesize');
            
            // Check file size limit
            if ($filesize > $this->uploadValidator->getMaxFileSize()) {
                throw new \Exception('File size exceeds maximum allowed size');
            }
            
            // Generate unique upload ID
            $uploadId = $this->chunkManager->generateUploadId();
            
            // Recommended chunk size: 1MB
            $chunkSize = 1024 * 1024;
            
            return response()->json([
                'success' => true,
                'uploadId' => $uploadId,
                'chunkSize' => $chunkSize,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Upload file chunk
     * 
     * POST /api/filemanager/upload/chunk
     * Body: multipart/form-data
     *   - chunk: Chunk file
     *   - uploadId: Upload session ID
     *   - chunkIndex: Current chunk index (0-based)
     *   - totalChunks: Total number of chunks
     *   - filename: Original filename
     *   - path: Destination directory
     */
    public function uploadChunk(Request $request): JsonResponse
    {
        $request->validate([
            'chunk' => 'required|file',
            'uploadId' => 'required|string',
            'chunkIndex' => 'required|integer|min:0',
            'totalChunks' => 'required|integer|min:1',
            'filename' => 'required|string',
            'path' => 'nullable|string',
        ]);

        try {
            $chunk = $request->file('chunk');
            $uploadId = $request->input('uploadId');
            $chunkIndex = (int)$request->input('chunkIndex');
            $totalChunks = (int)$request->input('totalChunks');
            $filename = $request->input('filename');
            $destinationPath = $request->input('path', '') ?? '';
            
            // Validate chunk
            $this->uploadValidator->validateChunk($chunk, 1024 * 1024);
            
            // Store chunk
            $this->chunkManager->storeChunk($chunk, $uploadId, $chunkIndex);
            
            // Check if all chunks uploaded
            $isComplete = $this->chunkManager->allChunksUploaded($uploadId, $totalChunks);
            
            if ($isComplete) {
                // Merge chunks into final file
                $relativePath = $destinationPath ? "{$destinationPath}/{$filename}" : $filename;
                $userPath = $this->pathResolver->resolveUserPath($relativePath);
                $absolutePath = $this->pathResolver->getAbsolutePath($relativePath);
                
                $this->chunkManager->mergeChunks($uploadId, $totalChunks, $absolutePath);
                
                return response()->json([
                    'success' => true,
                    'complete' => true,
                    'message' => 'File uploaded successfully',
                    'path' => $relativePath,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'complete' => false,
                'message' => 'Chunk uploaded successfully',
                'chunkIndex' => $chunkIndex,
                'totalChunks' => $totalChunks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cancel chunked upload
     * 
     * POST /api/filemanager/upload/cancel
     * Body: {"uploadId": "upload_abc123"}
     */
    public function cancelChunkedUpload(Request $request): JsonResponse
    {
        $request->validate([
            'uploadId' => 'required|string',
        ]);

        try {
            $uploadId = $request->input('uploadId');
            $this->chunkManager->cleanupChunks($uploadId);
            
            return response()->json([
                'success' => true,
                'message' => 'Upload cancelled',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
