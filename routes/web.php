<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScreenshotController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('screenshots', [ScreenshotController::class, 'index']);

Route::get('screenshots/updateScreenshot/{website}', [ScreenshotController::class, 'updateScreenshot']);

Route::get('screenshots/getScreenshot/{website}', [ScreenshotController::class, 'getScreenshot']);

Route::get('screenshots/checkImageNeedsUpdate', [ScreenshotController::class, 'checkImageNeedsUpdate']);

//Route::get('screenshots/store', [ScreenshotController::class, 'store']);
