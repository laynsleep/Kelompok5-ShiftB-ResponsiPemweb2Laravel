<?php

use App\Http\Controllers\AspirationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('aspirasi.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::resource('aspirasi', AspirationController::class)
        ->parameters(['aspirasi' => 'aspiration']);

    Route::post('/aspirasi/{aspiration}/comments', [CommentController::class, 'store'])->name('aspirasi.comments.store');
});
