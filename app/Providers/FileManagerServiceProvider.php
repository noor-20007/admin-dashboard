<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\FileManager\Storage\StorageInterface;
use App\FileManager\Storage\LocalStorage;
use App\FileManager\Security\PathValidator;
use App\FileManager\Security\PathSanitizer;
use App\FileManager\Security\UserPathResolver;
use App\FileManager\Security\UploadValidator;
use App\FileManager\Services\ChunkManager;
use App\FileManager\Services\ArchiveService;
use App\FileManager\Services\PathManager;
use App\FileManager\Services\FileNamingService;

/**
 * File Manager Service Provider
 * 
 * Registers all File Manager services with dependency injection container.
 * This allows for easy testing and swapping of implementations.
 * 
 * To use S3 or other cloud storage:
 * 1. Create new class implementing StorageInterface (e.g., S3Storage)
 * 2. Update binding in this provider
 * 3. No changes needed in controllers or services
 */
class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services
     */
    public function register(): void
    {
        // Register Storage Interface
        // Change this binding to use different storage backend (S3, Azure, etc.)
        $this->app->singleton(StorageInterface::class, function ($app) {
            // Root path should be the base filemanager directory
            // UserPathResolver will add user_X subdirectory
            $rootPath = storage_path('filemanager');
            $namingService = $app->make(FileNamingService::class);
            
            // Ensure root exists
            if (!is_dir($rootPath)) {
                mkdir($rootPath, 0755, true);
            }
            
            return new LocalStorage($rootPath, $namingService);
        });

        // Register Security Services
        $this->app->singleton(PathValidator::class);
        $this->app->singleton(PathSanitizer::class);
        
        $this->app->singleton(UserPathResolver::class, function ($app) {
            return new UserPathResolver(
                $app->make(PathSanitizer::class),
                $app->make(PathValidator::class),
                storage_path('filemanager')
            );
        });

        $this->app->singleton(UploadValidator::class, function ($app) {
            // Configure max file size from config (default 100MB)
            $maxFileSize = config('filemanager.max_file_size', 104857600);
            
            return new UploadValidator($maxFileSize);
        });

        // Register Utility Services
        $this->app->singleton(ChunkManager::class);
        $this->app->singleton(PathManager::class);
        $this->app->singleton(FileNamingService::class);
        
        $this->app->singleton(ArchiveService::class, function ($app) {
            return new ArchiveService($app->make(StorageInterface::class));
        });
    }

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        // Ensure storage directory exists
        $storagePath = storage_path('filemanager');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/filemanager.php' => config_path('filemanager.php'),
        ], 'filemanager-config');
    }
}
