<?php

/*
|--------------------------------------------------------------------------
| Mautic Application Routes
|--------------------------------------------------------------------------
|
*/

use Combindma\Mautic\Http\Controllers\MauticController;
use Illuminate\Support\Facades\Route;

Route::get('integration/mautic', [MauticController::class, 'integration']);
