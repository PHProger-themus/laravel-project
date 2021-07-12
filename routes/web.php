<?php

use App\Http\Controllers\ChatAuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('chat.index');

Route::post('/auth', [ChatAuthController::class, 'auth'])->name('chat.auth');
Route::post('/logout', [ChatAuthController::class, 'logout'])->name('chat.logout');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.chat');
