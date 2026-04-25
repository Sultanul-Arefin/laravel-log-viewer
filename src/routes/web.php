<?php

use Illuminate\Support\Facades\Route;
use SultanulArefin\LogViewer\Http\Controllers\AuthController;
use SultanulArefin\LogViewer\Http\Controllers\LogController;
use SultanulArefin\LogViewer\Http\Middleware\LogViewerAuth;

Route::prefix('log-viewer')->middleware(['web'])->group(function () {

    // Public within the package
    Route::get('/login', [AuthController::class, 'showLogin'])->name('log-viewer.login');
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes
    Route::middleware([LogViewerAuth::class])->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('log-viewer.index');
        Route::delete('/clear', [LogController::class, 'clear'])->name('log-viewer.clear');

        Route::post('/logout', [AuthController::class, 'logout'])->name('log-viewer.logout');
    });
});