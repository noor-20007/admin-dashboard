<?php

namespace App\FileManager\Security;

use App\FileManager\Security\Exceptions\SecurityException;

/**
 * Path Validator - Prevents path traversal attacks
 * 
 * This class is critical for security. It prevents malicious users from
 * accessing files outside their designated directory using path traversal
 * techniques (e.g., "../../../etc/passwd").
 * 
 * Security Measures:
 * - Blocks ".." in paths
 * - Validates against allowed base paths
 * - Normalizes paths to prevent bypass attempts
 * - Rejects absolute paths
 * 
 * Design Decision: Fail-safe approach - reject anything suspicious
 */
class PathValidator
{
    /**
     * Validate path for security issues
     * 
     * @param string $path Path to validate
     * @param bool $allowEmpty Allow empty paths (for root directory)
     * @throws SecurityException If path is invalid or dangerous
     */
    public function validate(string $path, bool $allowEmpty = false): void
    {
        // Reject empty paths unless explicitly allowed
        if (empty(trim($path)) && !$allowEmpty) {
            throw SecurityException::invalidPath('Path cannot be empty');
        }
        
        // If path is empty and allowed, skip further validation
        if (empty(trim($path)) && $allowEmpty) {
            return;
        }

        // Reject absolute paths (should always be relative)
        if ($this->isAbsolutePath($path)) {
            throw SecurityException::invalidPath('Absolute paths are not allowed');
        }

        // Reject paths with ".." (path traversal attempt)
        if ($this->containsPathTraversal($path)) {
            throw SecurityException::pathTraversalAttempt($path);
        }

        // Reject paths with null bytes (security vulnerability)
        if (str_contains($path, "\0")) {
            throw SecurityException::invalidPath('Null bytes are not allowed in paths');
        }

        // Reject paths with dangerous characters
        if ($this->containsDangerousCharacters($path)) {
            throw SecurityException::invalidPath('Path contains dangerous characters');
        }
    }

    /**
     * Validate that path is within allowed base path
     * 
     * @param string $path Path to validate
     * @param string $basePath Allowed base path
     * @throws SecurityException If path escapes base path
     */
    public function validateWithinBasePath(string $path, string $basePath): void
    {
        $this->validate($path);

        $realPath = $this->normalizePath($basePath . '/' . $path);
        $realBasePath = $this->normalizePath($basePath);

        // Ensure the resolved path starts with the base path
        if (!str_starts_with($realPath, $realBasePath)) {
            throw SecurityException::pathOutsideBase($path, $basePath);
        }
    }

    /**
     * Check if path is absolute
     */
    private function isAbsolutePath(string $path): bool
    {
        // Unix absolute path
        if (str_starts_with($path, '/')) {
            return true;
        }

        // Windows absolute path (C:\, D:\, etc.)
        if (preg_match('/^[a-zA-Z]:[\\\\\/]/', $path)) {
            return true;
        }

        // UNC path (\\server\share)
        if (str_starts_with($path, '\\\\')) {
            return true;
        }

        return false;
    }

    /**
     * Check for path traversal attempts
     */
    private function containsPathTraversal(string $path): bool
    {
        // Check for ".." in any form
        $patterns = [
            '..',           // Direct parent reference
            '%2e%2e',       // URL encoded
            '..%2f',        // Mixed encoding
            '%2e%2e%2f',    // Full URL encoded
            '..\\',         // Windows style
            '..\/',         // Mixed separators
        ];

        $lowerPath = strtolower($path);
        
        foreach ($patterns as $pattern) {
            if (str_contains($lowerPath, strtolower($pattern))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for dangerous characters
     */
    private function containsDangerousCharacters(string $path): bool
    {
        // Reject control characters and other dangerous chars
        $dangerous = ['<', '>', ':', '"', '|', '?', '*', "\0"];
        
        foreach ($dangerous as $char) {
            if (str_contains($path, $char)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalize path for comparison
     */
    private function normalizePath(string $path): string
    {
        // Convert backslashes to forward slashes
        $path = str_replace('\\', '/', $path);
        
        // Remove duplicate slashes
        $path = preg_replace('#/+#', '/', $path);
        
        // Remove trailing slash
        $path = rtrim($path, '/');
        
        return $path;
    }
}
