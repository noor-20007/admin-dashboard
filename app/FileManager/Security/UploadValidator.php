<?php

namespace App\FileManager\Security;

use App\FileManager\Security\Exceptions\SecurityException;
use Illuminate\Http\UploadedFile;

/**
 * Upload Validator - Validates file uploads for security and constraints
 * 
 * This class validates uploaded files to prevent security issues and
 * enforce business rules (file size, type restrictions, etc.).
 * 
 * Security Measures:
 * - File size limits
 * - MIME type validation
 * - Extension whitelist
 * - Malicious file detection
 * 
 * Design Decision: Whitelist approach (allow known-good) rather than
 * blacklist (block known-bad) for better security.
 */
class UploadValidator
{
    // Default max file size: 100MB
    private int $maxFileSize;

    // Allowed MIME types (can be configured per deployment)
    private array $allowedMimeTypes;

    // Allowed file extensions
    private array $allowedExtensions;

    // Dangerous extensions that should never be allowed
    private const DANGEROUS_EXTENSIONS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht',
        'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar',
        'sh', 'bash', 'cgi', 'pl', 'py', 'rb', 'asp', 'aspx', 'jsp',
    ];

    public function __construct(
        int $maxFileSize = 104857600, // 100MB default
        array $allowedMimeTypes = [],
        array $allowedExtensions = []
    ) {
        $this->maxFileSize = $maxFileSize;
        $this->allowedMimeTypes = $allowedMimeTypes ?: $this->getDefaultAllowedMimeTypes();
        $this->allowedExtensions = $allowedExtensions ?: $this->getDefaultAllowedExtensions();
    }

    /**
     * Validate uploaded file
     * 
     * @param UploadedFile $file Uploaded file
     * @throws SecurityException If validation fails
     */
    public function validate(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw SecurityException::fileSizeExceeded($file->getSize(), $this->maxFileSize);
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw SecurityException::invalidFileType($mimeType, $this->allowedMimeTypes);
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions)) {
            throw SecurityException::invalidFileType($extension, $this->allowedExtensions);
        }

        // Block dangerous extensions
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            throw SecurityException::invalidFileType($extension, ['Executable files not allowed']);
        }

        // Additional security checks
        $this->checkForMaliciousContent($file);
    }

    /**
     * Validate chunk upload (less strict, final validation on merge)
     * 
     * @param UploadedFile $chunk Uploaded chunk
     * @param int $chunkSize Expected chunk size
     * @throws SecurityException If validation fails
     */
    public function validateChunk(UploadedFile $chunk, int $chunkSize): void
    {
        // Chunks should not exceed expected size
        if ($chunk->getSize() > $chunkSize * 1.1) { // 10% tolerance
            throw SecurityException::fileSizeExceeded($chunk->getSize(), $chunkSize);
        }
    }

    /**
     * Check for malicious content in file
     */
    private function checkForMaliciousContent(UploadedFile $file): void
    {
        // For text-based files, check for PHP tags and scripts
        $textMimeTypes = ['text/plain', 'text/html', 'text/css', 'application/json'];
        
        if (in_array($file->getMimeType(), $textMimeTypes)) {
            $content = file_get_contents($file->getRealPath());
            
            // Check for PHP tags
            if (preg_match('/<\?php|<\?=/i', $content)) {
                throw SecurityException::invalidFileType('PHP code', ['Clean text files only']);
            }
        }
    }

    /**
     * Get default allowed MIME types
     */
    private function getDefaultAllowedMimeTypes(): array
    {
        return [
            // Images
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            
            // Documents
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            
            // Text
            'text/plain', 'text/csv', 'text/html', 'text/css',
            
            // Archives
            'application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed',
            
            // Media
            'video/mp4', 'video/mpeg', 'video/quicktime',
            'audio/mpeg', 'audio/wav', 'audio/ogg',
            
            // Code
            'application/json', 'application/xml', 'text/xml',
        ];
    }

    /**
     * Get default allowed extensions
     */
    private function getDefaultAllowedExtensions(): array
    {
        return [
            // Images
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            
            // Documents
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv',
            
            // Archives
            'zip', 'rar', '7z', 'tar', 'gz',
            
            // Media
            'mp4', 'mpeg', 'mov', 'avi', 'mp3', 'wav', 'ogg',
            
            // Code (safe formats)
            'json', 'xml', 'css', 'md',
        ];
    }

    /**
     * Set custom max file size
     */
    public function setMaxFileSize(int $bytes): void
    {
        $this->maxFileSize = $bytes;
    }

    /**
     * Get max file size
     */
    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }
}
