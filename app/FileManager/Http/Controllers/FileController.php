<?php

namespace App\FileManager\Http\Controllers;

use App\FileManager\Storage\StorageInterface;
use App\FileManager\Security\UserPathResolver;
use App\FileManager\Security\PathValidator;
use App\FileManager\Services\PathManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * File Controller - Handles file and directory CRUD operations
 * 
 * This controller provides RESTful API endpoints for file management:
 * - List directory contents
 * - Create files and directories
 * - Delete files and directories
 * - Rename/move files and directories
 * - Copy files and directories
 * 
 * Security: All paths are validated and scoped to authenticated user's directory
 */
class FileController extends Controller
{
    public function __construct(
        private StorageInterface $storage,
        private UserPathResolver $pathResolver,
        private PathValidator $pathValidator,
        private PathManager $pathManager
    ) {}

    /**
     * List directory contents
     * 
     * GET /api/filemanager/list?path=documents/projects
     * 
     * Response:
     * {
     *   "success": true,
     *   "path": "documents/projects",
     *   "parent": "documents",
     *   "items": [
     *     {"name": "file.txt", "type": "file", "size": 1024, "modified": 1234567890},
     *     {"name": "subfolder", "type": "dir", "size": 0, "modified": 1234567890}
     *   ]
     * }
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $relativePath = $request->input('path', '') ?? '';
            
            // Resolve to user's directory
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            // Get directory listing
            $items = $this->storage->listDirectory($userPath);
            
            // Get parent path for navigation
            $parent = $this->pathManager->getParent($relativePath);
            
            return response()->json([
                'success' => true,
                'path' => $relativePath,
                'parent' => $parent,
                'items' => $items,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create new directory
     * 
     * POST /api/filemanager/create-directory
     * Body: {"path": "documents/new-folder"}
     */
    public function createDirectory(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $relativePath = $request->input('path');
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            $this->storage->createDirectory($userPath);
            
            return response()->json([
                'success' => true,
                'message' => 'Directory created successfully',
                'path' => $relativePath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Create new file
     * 
     * POST /api/filemanager/create-file
     * Body: {"path": "documents/note.txt", "content": "Hello World"}
     */
    public function createFile(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
            'content' => 'nullable|string',
        ]);

        try {
            $relativePath = $request->input('path');
            $content = $request->input('content', '');
            
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            $this->storage->createFile($userPath, $content);
            
            return response()->json([
                'success' => true,
                'message' => 'File created successfully',
                'path' => $relativePath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete file or directory
     * 
     * POST /filemanager/delete
     * Body: {"path": "documents/old-file.txt"}
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $relativePath = $request->input('path');
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            \Log::info('Delete request', ['relativePath' => $relativePath, 'userPath' => $userPath]);
            
            $this->storage->delete($userPath);
            
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Rename or move file/directory
     * 
     * POST /api/filemanager/move
     * Body: {"from": "documents/old.txt", "to": "documents/new.txt"}
     */
    public function move(Request $request): JsonResponse
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        try {
            $fromPath = $request->input('from');
            $toPath = $request->input('to');
            
            $userFromPath = $this->pathResolver->resolveUserPath($fromPath);
            $userToPath = $this->pathResolver->resolveUserPath($toPath);
            
            $this->storage->move($userFromPath, $userToPath);
            
            return response()->json([
                'success' => true,
                'message' => 'Moved successfully',
                'from' => $fromPath,
                'to' => $toPath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Copy file or directory
     * 
     * POST /api/filemanager/copy
     * Body: {"from": "documents/file.txt", "to": "backup/file.txt"}
     */
    public function copy(Request $request): JsonResponse
    {
        $request->validate([
            'from' => 'required|string',
            'to' => 'required|string',
        ]);

        try {
            $fromPath = $request->input('from');
            $toPath = $request->input('to');
            
            $userFromPath = $this->pathResolver->resolveUserPath($fromPath);
            $userToPath = $this->pathResolver->resolveUserPath($toPath);
            
            $this->storage->copy($userFromPath, $userToPath);
            
            return response()->json([
                'success' => true,
                'message' => 'Copied successfully',
                'from' => $fromPath,
                'to' => $toPath,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get file/directory info
     * 
     * GET /api/filemanager/info?path=documents/file.txt
     */
    public function info(Request $request): JsonResponse
    {
        try {
            $relativePath = $request->input('path', '') ?? '';
            $userPath = $this->pathResolver->resolveUserPath($relativePath);
            
            $info = [
                'path' => $relativePath,
                'name' => $this->pathManager->getFilename($relativePath),
                'type' => $this->storage->isDirectory($userPath) ? 'dir' : 'file',
                'size' => $this->storage->getSize($userPath),
                'modified' => $this->storage->getLastModified($userPath),
            ];
            
            if ($info['type'] === 'file') {
                $info['mime_type'] = $this->storage->getMimeType($userPath);
                $info['extension'] = $this->pathManager->getExtension($relativePath);
            }
            
            return response()->json([
                'success' => true,
                'info' => $info,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
