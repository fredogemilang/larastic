<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\AdminAccess;
use App\Livewire\Dashboard;
use App\Livewire\Posts\PostList;
use App\Livewire\Posts\PostEditor;
use App\Livewire\Categories\CategoryManager;
use App\Livewire\Tags\TagManager;
use App\Livewire\Pages\PageList;
use App\Livewire\Pages\PageEditor;
use App\Livewire\Media\MediaLibrary;
use App\Livewire\Settings\SiteSettings;
use App\Livewire\Settings\UserManager;
use App\Livewire\Export\ExportManager;
use App\Livewire\Import\WpImporter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes — /dns-ctrl
|--------------------------------------------------------------------------
*/
$adminPrefix = config('static-cms.admin_prefix', 'dns-ctrl');

Route::prefix($adminPrefix)
    ->middleware(['web', AdminAccess::class])
    ->name('admin.')
    ->group(function () {

        Route::get('/', Dashboard::class)->name('dashboard');

        // Posts
        Route::get('/posts', PostList::class)->name('posts.index');
        Route::get('/posts/create', PostEditor::class)->name('posts.create');
        Route::get('/posts/{id}/edit', PostEditor::class)->name('posts.edit');

        // Categories & Tags
        Route::get('/categories', CategoryManager::class)->name('categories.index');
        Route::get('/tags', TagManager::class)->name('tags.index');

        // Pages
        Route::get('/pages', PageList::class)->name('pages.index');
        Route::get('/pages/{id}/edit', PageEditor::class)->name('pages.edit');

        // Media
        Route::get('/media', MediaLibrary::class)->name('media.index');

        // Export & Import
        Route::get('/export', ExportManager::class)->name('export.index');
        Route::get('/import', WpImporter::class)->name('import.index');

        // Settings (super_admin only)
        Route::get('/settings', SiteSettings::class)->name('settings');
        Route::get('/users', UserManager::class)->name('users.index');
    });
