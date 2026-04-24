<?php

use Illuminate\Support\Facades\Route;
use SultanulArefin\LogViewer\Http\Controllers\LogController;

Route::prefix('log-viewer')->group(function () {
    Route::get('/', [LogController::class, 'index'])->name('log-viewer.index');
    Route::delete('/clear', [LogController::class, 'clear'])->name('log-viewer.clear');
});