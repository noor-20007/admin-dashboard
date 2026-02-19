<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Manager Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the File Manager system
    |
    */

    /**
     * Maximum file size in bytes (default: 100MB)
     */
    'max_file_size' => env('FILEMANAGER_MAX_FILE_SIZE', 104857600),

    /**
     * Storage driver (local, s3, azure, etc.)
     */
    'storage_driver' => env('FILEMANAGER_STORAGE_DRIVER', 'local'),

    /**
     * Local storage root path
     */
    'local_storage_path' => storage_path('filemanager'),

    /**
     * Temporary files path
     */
    'temp_path' => storage_path('filemanager/temp'),

    /**
     * Chunk size for uploads (in bytes, default: 1MB)
     */
    'chunk_size' => env('FILEMANAGER_CHUNK_SIZE', 1048576),

    /**
     * Temporary file cleanup age (in hours)
     */
    'temp_cleanup_age' => env('FILEMANAGER_TEMP_CLEANUP_AGE', 24),

    /**
     * Archive cleanup age (in hours)
     */
    'archive_cleanup_age' => env('FILEMANAGER_ARCHIVE_CLEANUP_AGE', 1),

    /**
     * Allowed file extensions (empty array = allow all)
     */
    'allowed_extensions' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'txt', 'csv', 'json', 'xml', 'md',
        'zip', 'rar', '7z', 'tar', 'gz',
        'mp4', 'mpeg', 'mov', 'avi', 'mp3', 'wav', 'ogg',
    ],

    /**
     * Allowed MIME types (empty array = allow all)
     */
    'allowed_mime_types' => [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain', 'text/csv', 'application/json', 'application/xml',
        'application/zip', 'application/x-rar-compressed',
        'video/mp4', 'audio/mpeg',
    ],

    /**
     * S3 Configuration (if using S3 storage)
     */
    's3' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
    ],

    /**
     * Azure Configuration (if using Azure storage)
     */
    'azure' => [
        'account_name' => env('AZURE_STORAGE_ACCOUNT_NAME'),
        'account_key' => env('AZURE_STORAGE_ACCOUNT_KEY'),
        'container' => env('AZURE_STORAGE_CONTAINER'),
    ],
];
