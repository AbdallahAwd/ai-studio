<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');
// Route::get('/{any}', function () {
//     return view('error.404');
// })->where('any', '.*');

Route::get('auth/google', [LoginController::class, 'redirect2Google'])->name('googleAuth');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('googleCallback');
