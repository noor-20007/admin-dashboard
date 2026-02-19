<?php

namespace App\FileManager\Security\Exceptions;

use Exception;

/**
 * Security-related exceptions
 */
class SecurityException extends Exception
{
    public static function pathTraversalAttempt(string $path): self
    {
        return new self("Path traversal attempt detected: {$path}", 403);
    }

    public static function pathOutsideBase(string $path, string $basePath): self
    {
        return new self("Path '{$path}' is outside allowed base path '{$basePath}'", 403);
    }

    public static function invalidPath(string $reason): self
    {
        return new self("Invalid path: {$reason}", 400);
    }

    public static function fileSizeExceeded(int $size, int $maxSize): self
    {
        return new self("File size {$size} exceeds maximum allowed size {$maxSize}", 413);
    }

    public static function invalidFileType(string $type, array $allowed): self
    {
        $allowedStr = implode(', ', $allowed);
        return new self("File type '{$type}' not allowed. Allowed types: {$allowedStr}", 415);
    }

    public static function unauthorizedAccess(string $path): self
    {
        return new self("Unauthorized access to: {$path}", 403);
    }
}
