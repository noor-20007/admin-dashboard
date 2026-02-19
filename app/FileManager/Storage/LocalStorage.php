<?php

namespace App\FileManager\Storage;

use App\FileManager\Storage\Exceptions\StorageException;
use App\FileManager\Services\FileNamingService;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToMoveFile;

/**
 * Local Storage Implementation using Flysystem
 * 
 * This implementation uses League\Flysystem for local filesystem operations.
 * Flysystem provides a consistent API and makes it easy to switch to cloud
 * storage (S3, Azure, etc.) without changing application code.
 * 
 * Design Decisions:
 * - Stream-based operations for memory efficiency
 * - Automatic file renaming on conflicts (file.txt → file (1).txt)
 * - Recursive directory operations
 * - Comprehensive error handling
 */
class LocalStorage implements StorageInterface
{
    private Filesystem $filesystem;
    private FileNamingService $namingService;

    public function __construct(
        string $rootPath,
        FileNamingService $namingService
    ) {
        $adapter = new LocalFilesystemAdapter($rootPath);
        $this->filesystem = new Filesystem($adapter);
        $this->namingService = $namingService;
    }

    /**
     * List directory contents sorted (directories first, then files)
     */
    public function listDirectory(string $path): array
    {
        try {
            $listing = $this->filesystem->listContents($path, false);
            $items = [];

            foreach ($listing as $item) {
                $items[] = [
                    'name' => basename($item->path()),
                    'path' => $item->path(),
                    'type' => $item->isDir() ? 'dir' : 'file',
                    'size' => $item->isFile() ? $item->fileSize() : 0,
                    'modified' => $item->lastModified(),
                ];
            }

            // Sort: directories first, then alphabetically
            usort($items, function ($a, $b) {
                if ($a['type'] === $b['type']) {
                    return strcasecmp($a['name'], $b['name']);
                }
                return $a['type'] === 'dir' ? -1 : 1;
            });

            return $items;
        } catch (\Exception $e) {
            throw StorageException::directoryNotFound($path);
        }
    }

    public function createDirectory(string $path): bool
    {
        try {
            $this->filesystem->createDirectory($path);
            return true;
        } catch (\Exception $e) {
            throw StorageException::cannotCreateDirectory($path);
        }
    }

    public function createFile(string $path, string $content): bool
    {
        try {
            $this->filesystem->write($path, $content);
            return true;
        } catch (UnableToWriteFile $e) {
            throw StorageException::cannotWriteFile($path);
        }
    }

    /**
     * Write file using stream - memory efficient for large files
     */
    public function writeStream(string $path, $stream): bool
    {
        try {
            // Check if file exists and auto-rename if needed
            if ($this->exists($path)) {
                $path = $this->namingService->getUniqueFileName($path, $this);
            }

            $this->filesystem->writeStream($path, $stream);
            
            if (is_resource($stream)) {
                fclose($stream);
            }
            
            return true;
        } catch (UnableToWriteFile $e) {
            throw StorageException::cannotWriteFile($path);
        }
    }

    /**
     * Read file as stream - memory efficient for large files
     */
    public function readStream(string $path)
    {
        try {
            return $this->filesystem->readStream($path);
        } catch (UnableToReadFile $e) {
            throw StorageException::cannotReadFile($path);
        }
    }

    /**
     * Delete file or directory recursively
     */
    public function delete(string $path): bool
    {
        try {
            // Log for debugging
            \Log::info('Attempting to delete', ['path' => $path, 'exists' => $this->exists($path)]);
            
            if (!$this->exists($path)) {
                throw new \Exception("Path does not exist: {$path}");
            }
            
            if ($this->isDirectory($path)) {
                $this->filesystem->deleteDirectory($path);
            } else {
                $this->filesystem->delete($path);
            }
            
            \Log::info('Delete successful', ['path' => $path]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Delete failed', ['path' => $path, 'error' => $e->getMessage()]);
            throw StorageException::cannotDeleteFile($path);
        }
    }

    public function move(string $from, string $to): bool
    {
        try {
            $this->filesystem->move($from, $to);
            return true;
        } catch (UnableToMoveFile $e) {
            throw StorageException::cannotMoveFile($from, $to);
        }
    }

    /**
     * Copy file or directory recursively
     */
    public function copy(string $from, string $to): bool
    {
        try {
            if ($this->isDirectory($from)) {
                return $this->copyDirectory($from, $to);
            }
            
            $this->filesystem->copy($from, $to);
            return true;
        } catch (UnableToCopyFile $e) {
            throw StorageException::cannotCopyFile($from, $to);
        }
    }

    /**
     * Recursively copy directory and all contents
     */
    private function copyDirectory(string $from, string $to): bool
    {
        $this->createDirectory($to);
        
        $items = $this->listDirectory($from);
        
        foreach ($items as $item) {
            $sourcePath = $from . '/' . $item['name'];
            $destPath = $to . '/' . $item['name'];
            
            if ($item['type'] === 'dir') {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                $this->filesystem->copy($sourcePath, $destPath);
            }
        }
        
        return true;
    }

    public function exists(string $path): bool
    {
        return $this->filesystem->fileExists($path) || 
               $this->filesystem->directoryExists($path);
    }

    public function isDirectory(string $path): bool
    {
        return $this->filesystem->directoryExists($path);
    }

    public function getSize(string $path): int
    {
        try {
            return $this->filesystem->fileSize($path);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getLastModified(string $path): int
    {
        try {
            return $this->filesystem->lastModified($path);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getMimeType(string $path): string
    {
        try {
            return $this->filesystem->mimeType($path);
        } catch (\Exception $e) {
            return 'application/octet-stream';
        }
    }
}
