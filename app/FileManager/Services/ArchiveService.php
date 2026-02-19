<?php

namespace App\FileManager\Services;

use App\FileManager\Storage\StorageInterface;
use ZipArchive;

/**
 * Archive Service - Creates ZIP archives for batch downloads
 * 
 * This service generates ZIP archives dynamically for downloading multiple
 * files/folders. It uses streaming where possible to avoid memory issues
 * with large archives.
 * 
 * Design Decision: Generate archives on-the-fly rather than storing them
 * to save disk space and ensure fresh content.
 */
class ArchiveService
{
    private StorageInterface $storage;
    private string $tempPath;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
        $this->tempPath = storage_path('filemanager/temp/archives');
        $this->ensureTempDirectoryExists();
    }

    /**
     * Create ZIP archive from multiple paths
     * 
     * @param array $paths Array of file/directory paths to include
     * @param string $archiveName Name for the archive
     * @return string Path to created archive
     */
    public function createArchive(array $paths, string $archiveName = 'download.zip'): string
    {
        $archivePath = $this->tempPath . '/' . uniqid('archive_') . '_' . $archiveName;
        
        $zip = new ZipArchive();
        
        if ($zip->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Cannot create ZIP archive');
        }

        try {
            foreach ($paths as $path) {
                $this->addToArchive($zip, $path);
            }

            $zip->close();
            
            return $archivePath;
        } catch (\Exception $e) {
            $zip->close();
            
            if (file_exists($archivePath)) {
                unlink($archivePath);
            }
            
            throw $e;
        }
    }

    /**
     * Add file or directory to ZIP archive
     * 
     * @param ZipArchive $zip ZIP archive instance
     * @param string $path Path to add
     * @param string $zipPath Path within ZIP (for recursion)
     */
    private function addToArchive(ZipArchive $zip, string $path, string $zipPath = ''): void
    {
        if ($this->storage->isDirectory($path)) {
            $this->addDirectoryToArchive($zip, $path, $zipPath);
        } else {
            $this->addFileToArchive($zip, $path, $zipPath);
        }
    }

    /**
     * Add single file to archive using stream
     */
    private function addFileToArchive(ZipArchive $zip, string $path, string $zipPath = ''): void
    {
        $filename = $zipPath ?: basename($path);
        
        // Use stream to add file (memory efficient)
        $stream = $this->storage->readStream($path);
        $zip->addFromString($filename, stream_get_contents($stream));
        fclose($stream);
    }

    /**
     * Recursively add directory to archive
     */
    private function addDirectoryToArchive(ZipArchive $zip, string $path, string $zipPath = ''): void
    {
        $dirName = $zipPath ?: basename($path);
        
        // Add directory entry
        $zip->addEmptyDir($dirName);
        
        // Add all contents
        $items = $this->storage->listDirectory($path);
        
        foreach ($items as $item) {
            $itemPath = $path . '/' . $item['name'];
            $itemZipPath = $dirName . '/' . $item['name'];
            
            if ($item['type'] === 'dir') {
                $this->addDirectoryToArchive($zip, $itemPath, $itemZipPath);
            } else {
                $this->addFileToArchive($zip, $itemPath, $itemZipPath);
            }
        }
    }

    /**
     * Clean up temporary archive file
     * 
     * @param string $archivePath Path to archive file
     */
    public function cleanupArchive(string $archivePath): void
    {
        if (file_exists($archivePath)) {
            unlink($archivePath);
        }
    }

    /**
     * Clean up old archive files (older than 1 hour)
     * 
     * Should be called periodically via scheduled task
     */
    public function cleanupOldArchives(): int
    {
        $cleaned = 0;
        $maxAge = time() - 3600; // 1 hour

        if (!is_dir($this->tempPath)) {
            return 0;
        }

        $files = glob($this->tempPath . '/archive_*');

        foreach ($files as $file) {
            if (filemtime($file) < $maxAge) {
                unlink($file);
                $cleaned++;
            }
        }

        return $cleaned;
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
     * Get estimated archive size (approximate)
     * 
     * @param array $paths Paths to include in archive
     * @return int Estimated size in bytes
     */
    public function estimateArchiveSize(array $paths): int
    {
        $totalSize = 0;

        foreach ($paths as $path) {
            $totalSize += $this->calculatePathSize($path);
        }

        // ZIP compression typically achieves 20-30% reduction for mixed content
        return (int)($totalSize * 0.75);
    }

    /**
     * Calculate total size of path (recursive for directories)
     */
    private function calculatePathSize(string $path): int
    {
        if (!$this->storage->isDirectory($path)) {
            return $this->storage->getSize($path);
        }

        $totalSize = 0;
        $items = $this->storage->listDirectory($path);

        foreach ($items as $item) {
            $itemPath = $path . '/' . $item['name'];
            $totalSize += $this->calculatePathSize($itemPath);
        }

        return $totalSize;
    }
}
