<?php

/*
|--------------------------------------------------------------------------
| Mautic Application Routes
|--------------------------------------------------------------------------
|
*/

use Combindma\Mautic\Http\Controllers\MauticController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::get('integration/mautic', [MauticController::class, 'login']);
    Route::get('integration/mautic/callback', [MauticController::class, 'callback']);
});
