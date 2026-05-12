<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\Users\UserExportController;

Route::group([
    'prefix' => config('boilerplate.app.prefix', ''),
    'middleware' => ['web', 'boilerplate.locale']
], function () {

    Route::group(['middleware' => ['boilerplate.auth']], function () {

        // Export route
        Route::get('users/export', [UserExportController::class, 'export'])
            ->name('boilerplate.users.export');

        Route::prefix('file-manager')->as('boilerplate.file-manager.')->group(function () {
            Route::get('/', [FileManagerController::class, 'index'])->name('index');
            Route::get('create', [FileManagerController::class, 'create'])->name('create');
            Route::post('/', [FileManagerController::class, 'store'])->name('store');
            Route::get('{fileManager}/preview', [FileManagerController::class, 'preview'])->name('preview');
            Route::get('{fileManager}/download', [FileManagerController::class, 'download'])->name('download');
            Route::delete('{fileManager}', [FileManagerController::class, 'destroy'])->name('destroy');
        });

    });

});
