<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('glitch');
});
Route::get('/test', function () {
    return view('welcome');
});

Route::post('/download-video', [DownloadController::class, 'downloads'])->name('video.download');

Route::get('/glitch',[MainController::class,'index'])->name('glitch.index');
Route::get('/login_page',[LoginController::class,'index'])->name('login.index');
Route::get('/register_page',[RegisterController::class,'index'])->name('register.index');
Route::get('/user_page',[UserController::class,'index'])->name('user.index');

Route::post('/login_page',[LoginController::class,'store'])->name('login.store');
Route::post('/register_page',[RegisterController::class,'store'])->name('register.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
