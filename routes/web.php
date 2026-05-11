<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreviewController;

// Development Preview Routes
if (app()->environment('local', 'development')) {
    // Assets
    Route::get('/assets/css/{file}', [PreviewController::class, 'assetCss']);
    Route::get('/assets/js/{file}', [PreviewController::class, 'assetJs']);
    Route::get('/assets/img/{path}', [PreviewController::class, 'assetImg'])->where('path', '.*');
    Route::get('/assets/media/{path}', [PreviewController::class, 'assetMediaCatchAll'])->where('path', '.*');

    $blogPrefix = config('static-cms.blog.url_prefix', 'blog');
    $adminPrefix = config('static-cms.admin_prefix', 'dns-ctrl');

    // ──── Indonesian (default locale) ────
    Route::get('/', [PreviewController::class, 'home']);
    Route::get("/{$blogPrefix}", [PreviewController::class, 'blogIndex']);
    Route::get("/{$blogPrefix}/page/{page}", [PreviewController::class, 'blogIndex'])->where('page', '[0-9]+');
    Route::get("/{$blogPrefix}/{slug}", [PreviewController::class, 'blogPost']);

    // ──── English locale (/en/...) ────
    Route::prefix('en')->group(function () use ($blogPrefix, $adminPrefix) {
        Route::get('/', [PreviewController::class, 'home']);
        Route::get('/home', fn () => redirect('/en/'));

        Route::get("/{$blogPrefix}", [PreviewController::class, 'blogIndex']);
        Route::get("/{$blogPrefix}/page/{page}", [PreviewController::class, 'blogIndex'])->where('page', '[0-9]+');
        Route::get("/{$blogPrefix}/{slug}", [PreviewController::class, 'blogPost']);

        // English pages
        Route::get('/{slug}', [PreviewController::class, 'page'])->where('slug', '^(?!' . $adminPrefix . '|login|logout|assets).*$');
    });

    // Static Pages (Indonesian) — must be last
    Route::get('/{slug}', [PreviewController::class, 'page'])->where('slug', '^(?!' . $adminPrefix . '|login|logout|assets|en).*$');
} else {
    Route::get('/', function () {
        return redirect()->route('login');
    });
}

// Load admin panel routes
require __DIR__ . '/admin.php';
