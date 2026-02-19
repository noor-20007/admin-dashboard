<?php

namespace App\FileManager\Security;

/**
 * Path Sanitizer - Cleans and normalizes user input paths
 * 
 * This class sanitizes user-provided paths to ensure they are safe
 * and consistent. It removes dangerous characters, normalizes separators,
 * and ensures paths are in a standard format.
 * 
 * Design Decision: Sanitize first, validate second (defense in depth)
 */
class PathSanitizer
{
    /**
     * Sanitize path input from user
     * 
     * @param string $path Raw path from user input
     * @return string Sanitized path
     */
    public function sanitize(string $path): string
    {
        // Trim whitespace
        $path = trim($path);

        // Remove null bytes
        $path = str_replace("\0", '', $path);

        // Normalize directory separators to forward slash
        $path = str_replace('\\', '/', $path);

        // Remove duplicate slashes
        $path = preg_replace('#/+#', '/', $path);

        // Remove leading slash (paths should be relative)
        $path = ltrim($path, '/');

        // Remove trailing slash
        $path = rtrim($path, '/');

        // Remove any remaining dangerous characters
        $path = $this->removeDangerousCharacters($path);

        return $path;
    }

    /**
     * Sanitize filename (more restrictive than path)
     * 
     * @param string $filename Raw filename from user input
     * @return string Sanitized filename
     */
    public function sanitizeFilename(string $filename): string
    {
        // Remove path separators from filename
        $filename = str_replace(['/', '\\'], '', $filename);

        // Remove dangerous characters
        $filename = $this->removeDangerousCharacters($filename);

        // Remove leading/trailing dots and spaces
        $filename = trim($filename, '. ');

        // If filename is empty after sanitization, use default
        if (empty($filename)) {
            $filename = 'unnamed_' . time();
        }

        return $filename;
    }

    /**
     * Remove characters that could be dangerous in filenames
     */
    private function removeDangerousCharacters(string $input): string
    {
        // Remove control characters
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);

        // Remove potentially dangerous characters
        $dangerous = ['<', '>', ':', '"', '|', '?', '*'];
        $input = str_replace($dangerous, '', $input);

        return $input;
    }

    /**
     * Join path segments safely
     * 
     * @param string ...$segments Path segments to join
     * @return string Joined and sanitized path
     */
    public function joinPaths(string ...$segments): string
    {
        $sanitized = array_map([$this, 'sanitize'], $segments);
        $sanitized = array_filter($sanitized, fn($s) => $s !== '');
        
        return implode('/', $sanitized);
    }
}
