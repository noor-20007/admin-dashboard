<?php

namespace App\FileManager\Storage;

/**
 * Storage Interface - Abstraction for filesystem operations
 * 
 * This interface provides a unified API for file operations across different
 * storage backends (local, S3, Azure, etc.). All implementations must handle
 * streams to avoid memory issues with large files.
 * 
 * Design Decision: Using streams throughout ensures scalability for large files
 * and prevents memory exhaustion in production environments.
 */
interface StorageInterface
{
    /**
     * List directory contents with metadata
     * 
     * @param string $path Directory path
     * @return array Array of file/directory metadata
     * [
     *   ['name' => 'file.txt', 'type' => 'file', 'size' => 1024, 'modified' => timestamp],
     *   ['name' => 'folder', 'type' => 'dir', 'size' => 0, 'modified' => timestamp]
     * ]
     */
    public function listDirectory(string $path): array;

    /**
     * Create a new directory
     * 
     * @param string $path Directory path
     * @return bool Success status
     */
    public function createDirectory(string $path): bool;

    /**
     * Create or overwrite a file with content
     * 
     * @param string $path File path
     * @param string $content File content
     * @return bool Success status
     */
    public function createFile(string $path, string $content): bool;

    /**
     * Write file using stream (memory-efficient for large files)
     * 
     * @param string $path File path
     * @param resource $stream File stream
     * @return bool Success status
     */
    public function writeStream(string $path, $stream): bool;

    /**
     * Read file as stream (memory-efficient for large files)
     * 
     * @param string $path File path
     * @return resource File stream
     */
    public function readStream(string $path);

    /**
     * Delete file or directory (recursive for directories)
     * 
     * @param string $path File or directory path
     * @return bool Success status
     */
    public function delete(string $path): bool;

    /**
     * Rename/move file or directory
     * 
     * @param string $from Source path
     * @param string $to Destination path
     * @return bool Success status
     */
    public function move(string $from, string $to): bool;

    /**
     * Copy file or directory (recursive for directories)
     * 
     * @param string $from Source path
     * @param string $to Destination path
     * @return bool Success status
     */
    public function copy(string $from, string $to): bool;

    /**
     * Check if file or directory exists
     * 
     * @param string $path Path to check
     * @return bool Existence status
     */
    public function exists(string $path): bool;

    /**
     * Check if path is a directory
     * 
     * @param string $path Path to check
     * @return bool True if directory
     */
    public function isDirectory(string $path): bool;

    /**
     * Get file size in bytes
     * 
     * @param string $path File path
     * @return int File size
     */
    public function getSize(string $path): int;

    /**
     * Get last modified timestamp
     * 
     * @param string $path File or directory path
     * @return int Unix timestamp
     */
    public function getLastModified(string $path): int;

    /**
     * Get MIME type of file
     * 
     * @param string $path File path
     * @return string MIME type
     */
    public function getMimeType(string $path): string;
}
