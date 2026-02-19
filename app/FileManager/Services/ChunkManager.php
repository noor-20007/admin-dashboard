<?php

namespace App\FileManager\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Chunk Manager - Handles chunked file uploads
 * 
 * Large file uploads are split into chunks on the client side and uploaded
 * sequentially. This service manages temporary storage of chunks and merges
 * them into the final file once all chunks are received.
 * 
 * Benefits:
 * - Supports large files without memory issues
 * - Resumable uploads (can retry failed chunks)
 * - Better progress tracking
 * - Works around server upload limits
 * 
 * Design Decision: Store chunks in temporary directory with unique session ID
 * to prevent conflicts between concurrent uploads.
 */
class ChunkManager
{
    private string $tempPath;

    public function __construct()
    {
        $this->tempPath = storage_path('filemanager/temp');
        $this->ensureTempDirectoryExists();
    }

    /**
     * Store uploaded chunk
     * 
     * @param UploadedFile $chunk Uploaded chunk file
     * @param string $uploadId Unique upload session ID
     * @param int $chunkIndex Current chunk index (0-based)
     * @return bool Success status
     */
    public function storeChunk(UploadedFile $chunk, string $uploadId, int $chunkIndex): bool
    {
        $chunkPath = $this->getChunkPath($uploadId, $chunkIndex);
        $chunkDir = dirname($chunkPath);

        // Create upload directory if needed
        if (!is_dir($chunkDir)) {
            mkdir($chunkDir, 0755, true);
        }

        // Move chunk to temporary storage
        return $chunk->move($chunkDir, basename($chunkPath)) !== false;
    }

    /**
     * Check if all chunks have been uploaded
     * 
     * @param string $uploadId Unique upload session ID
     * @param int $totalChunks Total number of chunks expected
     * @return bool True if all chunks are present
     */
    public function allChunksUploaded(string $uploadId, int $totalChunks): bool
    {
        for ($i = 0; $i < $totalChunks; $i++) {
            if (!file_exists($this->getChunkPath($uploadId, $i))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Merge all chunks into final file
     * 
     * This method uses streaming to merge chunks without loading
     * the entire file into memory, making it suitable for large files.
     * 
     * @param string $uploadId Unique upload session ID
     * @param int $totalChunks Total number of chunks
     * @param string $destinationPath Final file path
     * @return bool Success status
     */
    public function mergeChunks(string $uploadId, int $totalChunks, string $destinationPath): bool
    {
        $destinationDir = dirname($destinationPath);
        
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        // Open destination file for writing
        $destination = fopen($destinationPath, 'wb');
        
        if (!$destination) {
            return false;
        }

        try {
            // Stream each chunk into destination file
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = $this->getChunkPath($uploadId, $i);
                
                if (!file_exists($chunkPath)) {
                    throw new \RuntimeException("Chunk {$i} not found");
                }

                // Read chunk and write to destination (streaming)
                $chunk = fopen($chunkPath, 'rb');
                
                if (!$chunk) {
                    throw new \RuntimeException("Cannot open chunk {$i}");
                }

                // Stream chunk data (8KB buffer)
                while (!feof($chunk)) {
                    $buffer = fread($chunk, 8192);
                    fwrite($destination, $buffer);
                }

                fclose($chunk);
            }

            fclose($destination);
            
            // Clean up chunks after successful merge
            $this->cleanupChunks($uploadId);
            
            return true;
        } catch (\Exception $e) {
            fclose($destination);
            
            // Remove incomplete destination file
            if (file_exists($destinationPath)) {
                unlink($destinationPath);
            }
            
            throw $e;
        }
    }

    /**
     * Clean up temporary chunks for an upload session
     * 
     * @param string $uploadId Unique upload session ID
     */
    public function cleanupChunks(string $uploadId): void
    {
        $uploadDir = $this->getUploadDirectory($uploadId);
        
        if (is_dir($uploadDir)) {
            $this->deleteDirectory($uploadDir);
        }
    }

    /**
     * Clean up old temporary files (older than 24 hours)
     * 
     * This should be called periodically (e.g., via scheduled task)
     * to prevent disk space issues from abandoned uploads.
     */
    public function cleanupOldTemporaryFiles(): int
    {
        $cleaned = 0;
        $maxAge = time() - (24 * 3600); // 24 hours

        if (!is_dir($this->tempPath)) {
            return 0;
        }

        $directories = glob($this->tempPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $dir) {
            // Check directory modification time
            if (filemtime($dir) < $maxAge) {
                $this->deleteDirectory($dir);
                $cleaned++;
            }
        }

        return $cleaned;
    }

    /**
     * Get path for specific chunk
     */
    private function getChunkPath(string $uploadId, int $chunkIndex): string
    {
        return $this->getUploadDirectory($uploadId) . "/chunk_{$chunkIndex}";
    }

    /**
     * Get upload directory for session
     */
    private function getUploadDirectory(string $uploadId): string
    {
        return $this->tempPath . '/' . $uploadId;
    }

    /**
     * Ensure temp directory exists
     */
    private function ensureTempDirectoryExists(): void
    {
        if (!is_dir($this->tempPath)) {
            mkdir($this->tempPath, 0755, true);
        }
    }

    /**
     * Recursively delete directory
     */
    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }

        return rmdir($dir);
    }

    /**
     * Generate unique upload ID
     */
    public function generateUploadId(): string
    {
        return uniqid('upload_', true);
    }
}
