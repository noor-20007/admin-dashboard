<?php

namespace App\FileManager\Security;

use Illuminate\Support\Facades\Auth;

/**
 * User Path Resolver - Manages user-isolated directories
 * 
 * This class ensures each user has their own isolated directory space.
 * It automatically prefixes all paths with the user's home directory,
 * preventing users from accessing each other's files.
 * 
 * Design Decision: User isolation at the path level provides defense in depth.
 * Even if other security measures fail, users cannot escape their home directory.
 * 
 * Directory Structure:
 * storage/filemanager/
 * ├── user_1/
 * │   ├── documents/
 * │   └── images/
 * ├── user_2/
 * │   └── projects/
 * └── user_3/
 */
class UserPathResolver
{
    private PathSanitizer $sanitizer;
    private PathValidator $validator;
    private string $baseStoragePath;

    public function __construct(
        PathSanitizer $sanitizer,
        PathValidator $validator,
        string $baseStoragePath = null
    ) {
        $this->sanitizer = $sanitizer;
        $this->validator = $validator;
        $this->baseStoragePath = $baseStoragePath ?? storage_path('filemanager');
    }

    /**
     * Resolve user path with automatic user directory prefix
     * 
     * @param string $relativePath User-provided relative path
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return string Full path within user's directory
     */
    public function resolveUserPath(string $relativePath, ?int $userId = null): string
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            throw new \RuntimeException('User must be authenticated');
        }

        // Sanitize the relative path
        $sanitizedPath = $this->sanitizer->sanitize($relativePath);

        // Validate for security issues (allow empty for root directory)
        if (!empty($sanitizedPath)) {
            $this->validator->validate($sanitizedPath, true);
        }

        // Build full path: base/user_X/relative/path
        $userDir = $this->getUserDirectory($userId);
        $fullPath = $sanitizedPath 
            ? $this->sanitizer->joinPaths($userDir, $sanitizedPath)
            : $userDir;

        // Final validation: ensure path is within user's directory
        // Skip validation if it's just the user directory itself
        if ($sanitizedPath !== '') {
            $this->validator->validateWithinBasePath($fullPath, $this->baseStoragePath);
        }

        return $fullPath;
    }

    /**
     * Get user's home directory path
     * 
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return string User's home directory path
     */
    public function getUserDirectory(?int $userId = null): string
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            throw new \RuntimeException('User must be authenticated');
        }

        return "user_{$userId}";
    }

    /**
     * Get absolute filesystem path for user
     * 
     * @param string $relativePath User-provided relative path
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return string Absolute filesystem path
     */
    public function getAbsolutePath(string $relativePath, ?int $userId = null): string
    {
        $userPath = $this->resolveUserPath($relativePath, $userId);
        return $this->baseStoragePath . '/' . $userPath;
    }

    /**
     * Initialize user's home directory if it doesn't exist
     * 
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return bool Success status
     */
    public function initializeUserDirectory(?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();
        $userDir = $this->getAbsolutePath('', $userId);

        if (!is_dir($userDir)) {
            return mkdir($userDir, 0755, true);
        }

        return true;
    }

    /**
     * Get base storage path
     * 
     * @return string Base storage path
     */
    public function getBaseStoragePath(): string
    {
        return $this->baseStoragePath;
    }
}
