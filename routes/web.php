<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/fix-storage', function () {
    // 1. Define robust paths relative to base_path (avoiding potentially wrong public_path())
    $target = storage_path('app/public');
    $link = base_path('../public_html/storage'); 
    
    $messages = [];

    // 2. Check APP_URL
    if (str_contains(env('APP_URL'), '127.0.0.1') || str_contains(env('APP_URL'), 'localhost')) {
        $messages[] = "⚠️ تنبيه هام: رابط الموقع في ملف .env ما زال (localhost). الصور لن تظهر حتى لو تم الرفع بنجاح.";
        $messages[] = "يرجى تعديل .env وتغيير APP_URL إلى رابط موقعك الحقيقي: https://your-domain.com";
        $messages[] = "---------------------------------------------------";
    }

    // 3. Clean up OLD link if it exists in the WRONG place (Admin_dashboard/public/storage)
    $wrongLink = base_path('public/storage');
    if (file_exists($wrongLink)) {
        // We just delete it to clean up, though it doesn't hurt functionality much
        if (is_link($wrongLink)) unlink($wrongLink);
        elseif (is_dir($wrongLink)) rmdir($wrongLink); // simplistic
    }

    // 4. Handle the REAL link in public_html
    if (file_exists($link)) {
        // Recursive delete to be safe
        if (is_link($link)) {
            unlink($link);
        } elseif (is_dir($link)) {
             $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($link, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }
            rmdir($link);
        } else {
            unlink($link);
        }
    }

    // 5. Create Symlink
    try {
        symlink($target, $link);
        $messages[] = "✅ تم إنشاء رابط التخزين بنجاح في: public_html/storage";
        $messages[] = "✅ الرابط يشير إلى: " . $target;
        return implode('<br>', $messages);
    } catch (\Exception $e) {
        return "❌ حدث خطأ: " . $e->getMessage();
    }
});

Route::get('/check-php-config', function() {
    return [
        'post_max_size' => ini_get('post_max_size') . ' (' . (int)ini_get('post_max_size') . ' raw)',
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'memory_limit' => ini_get('memory_limit'),
        'request_content_length' => $_SERVER['CONTENT_LENGTH'] ?? 'Not Set',
    ];
});

Route::any('/debug-upload', function () {
    $info = [];
    $info['Current Public Path'] = public_path();
    $info['Base Path'] = base_path();
    
    // Simulate what the Controller does
    if (request()->isMethod('post') && request()->hasFile('test_file')) {
        try {
            $file = request()->file('test_file');
            $imageName = 'test_' . time() . '.' . $file->extension();
            
            // This is exactly what your controller does:
            $destination = public_path('images'); 
            
            // Check if directory exists
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true); 
                $createdDir = true;
            }

            $file->move($destination, $imageName);
            
            return "✅ Upload Success!<br>" .
                   "<b>File Saved To:</b> " . $destination . '/' . $imageName . "<br>" .
                   "<b>Public Path Used:</b> " . public_path() . "<br>" .
                   "<b>Expected URL:</b> " . url('images/' . $imageName) . "<br>" .
                   "<h3>Check this:</h3> Is the 'File Saved To' path inside public_html??<br>" . 
                   "<a href='/debug-upload'>Back</a>";

        } catch (\Exception $e) {
            return "❌ Upload Failed: " . $e->getMessage() . "<br><a href='/debug-upload'>Back</a>";
        }
    }

    echo "<h1>Real Controller Simulation</h1>";
    echo "<p>Your dashboard uses <code>move(public_path('images'))</code>. Let's test EXACTLY that.</p>";
    
    echo "<h3>Current Config:</h3>";
    echo "<pre>" . print_r($info, true) . "</pre>";

    if (!str_contains(public_path(), 'public_html')) {
        echo "<h2 style='color:red'>⚠️ WARNING: public_path is NOT pointing to public_html!</h2>";
        echo "<p>It points to: <b>" . public_path() . "</b></p>";
        echo "<p>This is why your images are not showing. The Code creates them in the wrong place.</p>";
        echo "<p><b>Solution:</b> The AppServiceProvider fix is not active. Try clearing cache again below.</p>";
    } else {
        echo "<h2 style='color:green'>✅ Path looks correct (contains public_html)</h2>";
    }

    echo "<h3>Test Upload</h3>";
    echo "<form method='POST' enctype='multipart/form-data'>";
    echo csrf_field();
    echo "<input type='file' name='test_file' required>";
    echo "<button type='submit'>Simulate Controller Upload</button>";
    echo "</form>";
    
    echo "<hr>";
    echo "<a href='/debug-upload?clear=1' style='background:red; color:white; padding:10px;'>Force Clear Cache</a>";
    
    if (request()->has('clear')) {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        echo "<br><b>Cache Cleared! Refresh the page.</b>";
    }
});

// Allow POST for this route for the test
Route::post('/debug-upload', function() {
    return Route::getRoutes()->getByName('debug-testing')->run();
})->name('debug-testing'); // Hacky self-call or just duplicate logic

// Simpler: Just handle both in one closure logic above, but need Route::any


Route::get('/fix-permissions', function () {
    try {
        // Try to fix permissions for storage and subfolders
        $dirs = [
            storage_path('app'),
            storage_path('app/public'),
            storage_path('app/livewire-tmp'), // Common issue
            storage_path('framework/cache'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];
        
        foreach ($dirs as $dir) {
            if (!file_exists($dir)) @mkdir($dir, 0755, true);
            @chmod($dir, 0755);
        }
        
        return "Attempted to fix permissions (755) on crucial directories. Try uploading again.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/do-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "✅ تم تحديث قاعدة البيانات بنجاح (Migration Successful)!<br>Output:<br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "❌ خطأ أثناء التحديث: " . $e->getMessage();
    }
});

Route::get('/', function () {
    $locale = request()->get('lang', 'ar');
    app()->setLocale($locale);
    
    $slides = \App\Models\Slide::where('is_active', true)->orderBy('sort_order')->get();
    $setting = \App\Models\Setting::first();
    $services = \App\Models\Service::all();
    $members = \App\Models\Member::all();
    $skills = \App\Models\Skill::all();
    $categories = \App\Models\Category::all();
    $portfolios = \App\Models\Portfolio::with('category')->get();
    $posts = \App\Models\Post::latest()->take(3)->get();
    $timelines = \App\Models\Timeline::orderBy('year', 'desc')->get();
    return view('index', compact('slides', 'setting', 'services', 'members', 'skills', 'categories', 'portfolios', 'posts', 'timelines'));
});

// Auth Routes
Route::get('login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// File Manager Route
Route::middleware(['auth'])->group(function () {
    Route::get('/filemanager', [App\Http\Controllers\FileManagerViewController::class, 'index'])->name('filemanager.index');
});

// Include File Manager Web Routes
require __DIR__.'/filemanager-web.php';

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Language Routes
    Route::get('language/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');

    // Settings Routes
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Slides Routes
    Route::resource('slides', App\Http\Controllers\Admin\SlideController::class)->except(['show']);
    
    // Services Routes
    Route::resource('services', App\Http\Controllers\Admin\ServiceController::class)->except(['show']);

    // Portfolios Routes
    Route::resource('portfolios', App\Http\Controllers\Admin\PortfolioController::class)->except(['show']);

    // Posts Routes
    Route::resource('posts', App\Http\Controllers\Admin\PostController::class)->except(['show']);

    // Accounts Routes
    Route::resource('accounts', App\Http\Controllers\Admin\AccountController::class);

    // Groups Routes
    Route::resource('groups', App\Http\Controllers\Admin\GroupController::class);

    // Clients Routes
    Route::resource('clients', App\Http\Controllers\Admin\ClientController::class);

    // Members Routes
    Route::resource('members', App\Http\Controllers\Admin\MemberController::class)->except(['show']);

    // Users Routes
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);

    // Categories Routes
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show']);

    // Skills Routes
    Route::resource('skills', App\Http\Controllers\Admin\SkillController::class)->except(['show']);

    // Timelines Routes
    Route::resource('timelines', App\Http\Controllers\Admin\TimelineController::class)->except(['show']);

    // Currencies Routes
    Route::resource('currencies', App\Http\Controllers\Admin\CurrencyController::class)->except(['show']);
});
