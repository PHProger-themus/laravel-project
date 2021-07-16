<?php

use App\Http\Controllers\ChatAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('chat.index');

Route::post('/reg', [ChatAuthController::class, 'reg'])->name('chat.reg');
Route::post('/auth', [ChatAuthController::class, 'auth'])->name('chat.auth');
Route::post('/logout', [ChatAuthController::class, 'logout'])->name('chat.logout');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.chat');

Route::post('/send', [ChatController::class, 'sendMessage'])->name('chat.send-message');
Route::post('/edit', [ChatController::class, 'editMessage'])->name('chat.edit-message');
Route::post('/delete', [ChatController::class, 'deleteMessage'])->name('chat.delete-message');
Route::post('/like', [ChatController::class, 'likeMessage'])->name('chat.like-message');
Route::post('/pin', [ChatController::class, 'pinMessage'])->name('chat.pin-message');

Route::group(['prefix' => 'settings'], function () {
    Route::get('users', [SettingsController::class, 'usersSettings'])->name('chat.settings.users');
});
