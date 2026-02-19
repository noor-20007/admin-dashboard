<?php

namespace App\FileManager\Services;

/**
 * Path Manager - Utility service for path manipulation
 * 
 * Provides helper methods for common path operations like
 * normalization, joining, and extraction of path components.
 */
class PathManager
{
    /**
     * Normalize path (convert separators, remove duplicates)
     * 
     * @param string $path Path to normalize
     * @return string Normalized path
     */
    public function normalize(string $path): string
    {
        // Convert backslashes to forward slashes
        $path = str_replace('\\', '/', $path);
        
        // Remove duplicate slashes
        $path = preg_replace('#/+#', '/', $path);
        
        // Remove trailing slash
        $path = rtrim($path, '/');
        
        // Remove leading slash (paths should be relative)
        $path = ltrim($path, '/');
        
        return $path;
    }

    /**
     * Join path segments
     * 
     * @param string ...$segments Path segments to join
     * @return string Joined path
     */
    public function join(string ...$segments): string
    {
        $normalized = array_map([$this, 'normalize'], $segments);
        $filtered = array_filter($normalized, fn($s) => $s !== '');
        
        return implode('/', $filtered);
    }

    /**
     * Get parent directory path
     * 
     * @param string $path File or directory path
     * @return string Parent directory path
     */
    public function getParent(string $path): string
    {
        $path = $this->normalize($path);
        $parent = dirname($path);
        
        return $parent === '.' ? '' : $parent;
    }

    /**
     * Get filename from path
     * 
     * @param string $path File path
     * @return string Filename
     */
    public function getFilename(string $path): string
    {
        return basename($path);
    }

    /**
     * Get file extension
     * 
     * @param string $path File path
     * @return string Extension (without dot)
     */
    public function getExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Get filename without extension
     * 
     * @param string $path File path
     * @return string Filename without extension
     */
    public function getFilenameWithoutExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Check if path is root
     * 
     * @param string $path Path to check
     * @return bool True if root path
     */
    public function isRoot(string $path): bool
    {
        return $this->normalize($path) === '';
    }

    /**
     * Get depth of path (number of directory levels)
     * 
     * @param string $path Path to analyze
     * @return int Depth level
     */
    public function getDepth(string $path): int
    {
        $path = $this->normalize($path);
        
        if ($path === '') {
            return 0;
        }
        
        return substr_count($path, '/') + 1;
    }
}
