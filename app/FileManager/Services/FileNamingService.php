<?php

namespace App\FileManager\Services;

use App\FileManager\Storage\StorageInterface;

/**
 * File Naming Service - Handles automatic file renaming on conflicts
 * 
 * When a file already exists, this service generates a unique name by
 * appending a number in parentheses: file.txt → file (1).txt → file (2).txt
 * 
 * This prevents overwriting existing files and provides a user-friendly
 * naming convention similar to Windows Explorer and Google Drive.
 */
class FileNamingService
{
    /**
     * Get unique filename if file already exists
     * 
     * @param string $path Original file path
     * @param StorageInterface $storage Storage instance to check existence
     * @return string Unique file path
     */
    public function getUniqueFileName(string $path, StorageInterface $storage): string
    {
        if (!$storage->exists($path)) {
            return $path;
        }

        $directory = dirname($path);
        $filename = basename($path);
        
        // Split filename into name and extension
        $pathInfo = pathinfo($filename);
        $name = $pathInfo['filename'];
        $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';

        $counter = 1;
        
        // Keep incrementing until we find a unique name
        do {
            $newFilename = "{$name} ({$counter}){$extension}";
            $newPath = $directory === '.' ? $newFilename : "{$directory}/{$newFilename}";
            $counter++;
        } while ($storage->exists($newPath) && $counter < 1000); // Safety limit

        return $newPath;
    }

    /**
     * Generate safe filename from user input
     * 
     * @param string $filename Original filename
     * @return string Safe filename
     */
    public function generateSafeFilename(string $filename): string
    {
        // Remove path separators
        $filename = str_replace(['/', '\\'], '', $filename);
        
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        // Trim dots and underscores from start/end
        $filename = trim($filename, '._');
        
        // If empty, generate timestamp-based name
        if (empty($filename)) {
            $filename = 'file_' . time();
        }
        
        return $filename;
    }
}
