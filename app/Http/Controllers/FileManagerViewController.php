<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * File Manager View Controller
 * 
 * Handles rendering the file manager interface
 */
class FileManagerViewController extends Controller
{
    /**
     * Show file manager interface
     */
    public function index()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Initialize user directory if it doesn't exist
        $pathResolver = app(\App\FileManager\Security\UserPathResolver::class);
        $pathResolver->initializeUserDirectory();

        return view('filemanager.index');
    }
}
