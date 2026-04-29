<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;

Route::get('/', function () {
    return view('glitch');
});
Route::get('/test', function () {
    return view('welcome');
});

Route::post('/download-video', [DownloadController::class, 'downloads'])->name('video.download');

Route::get('/main_page',[MainController::class,'index'])->name('main.index');
Route::get('/login_page',[LoginController::class,'index'])->name('login.index');
Route::get('/register_page',[RegisterController::class,'index'])->name('register.index');
Route::get('/user_page',[UserController::class,'index'])->name('user.index');
