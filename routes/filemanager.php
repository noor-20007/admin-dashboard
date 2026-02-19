<?php

use Illuminate\Support\Facades\Route;
use App\FileManager\Http\Controllers\FileController;
use App\FileManager\Http\Controllers\UploadController;
use App\FileManager\Http\Controllers\DownloadController;

/**
 * File Manager API Routes
 * 
 * All routes require authentication middleware.
 * Uses session-based authentication (web middleware).
 */

Route::middleware(['web', 'auth'])->prefix('filemanager')->name('api.filemanager.')->group(function () {
    
    // File & Directory Operations
    Route::get('/list', [FileController::class, 'list'])->name('list');
    Route::get('/info', [FileController::class, 'info'])->name('info');
    Route::post('/create-directory', [FileController::class, 'createDirectory'])->name('create-directory');
    Route::post('/create-file', [FileController::class, 'createFile'])->name('create-file');
    Route::delete('/delete', [FileController::class, 'delete'])->name('delete');
    Route::post('/move', [FileController::class, 'move'])->name('move');
    Route::post('/copy', [FileController::class, 'copy'])->name('copy');
    
    // Upload Operations
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
    Route::post('/upload/init', [UploadController::class, 'initChunkedUpload'])->name('upload.init');
    Route::post('/upload/chunk', [UploadController::class, 'uploadChunk'])->name('upload.chunk');
    Route::post('/upload/cancel', [UploadController::class, 'cancelChunkedUpload'])->name('upload.cancel');
    
    // Download Operations
    Route::get('/download', [DownloadController::class, 'download'])->name('download');
    Route::post('/download/batch', [DownloadController::class, 'downloadBatch'])->name('download.batch');
    Route::post('/download/info', [DownloadController::class, 'downloadInfo'])->name('download.info');
});
