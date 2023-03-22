<?php

/*
|--------------------------------------------------------------------------
| Mautic Application Routes
|--------------------------------------------------------------------------
|
*/

use Combindma\Mautic\Http\Controllers\MauticController;
use Illuminate\Support\Facades\Route;

Route::get('login/mautic', [MauticController::class, 'login']);
Route::get('login/mautic/callback', [MauticController::class, 'callback']);
