<?php

namespace App\FileManager\Storage\Exceptions;

use Exception;

/**
 * Base exception for all storage-related errors
 */
class StorageException extends Exception
{
    public static function fileNotFound(string $path): self
    {
        return new self("File not found: {$path}");
    }

    public static function directoryNotFound(string $path): self
    {
        return new self("Directory not found: {$path}");
    }

    public static function cannotCreateDirectory(string $path): self
    {
        return new self("Cannot create directory: {$path}");
    }

    public static function cannotWriteFile(string $path): self
    {
        return new self("Cannot write file: {$path}");
    }

    public static function cannotReadFile(string $path): self
    {
        return new self("Cannot read file: {$path}");
    }

    public static function cannotDeleteFile(string $path): self
    {
        return new self("Cannot delete file: {$path}");
    }

    public static function cannotCopyFile(string $from, string $to): self
    {
        return new self("Cannot copy from {$from} to {$to}");
    }

    public static function cannotMoveFile(string $from, string $to): self
    {
        return new self("Cannot move from {$from} to {$to}");
    }
}
