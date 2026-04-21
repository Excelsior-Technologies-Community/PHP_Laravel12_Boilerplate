<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserExportController;

Route::group([
    'prefix' => config('boilerplate.app.prefix', ''),
    'middleware' => ['web', 'boilerplate.locale']
], function () {

    Route::group(['middleware' => ['boilerplate.auth']], function () {

        // Export route
        Route::get('users/export', [UserExportController::class, 'export'])
            ->name('boilerplate.users.export');

    });

});