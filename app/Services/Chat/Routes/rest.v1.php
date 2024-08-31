<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Services\Chat\Controllers\ChatController;

Route::post('/chats', [ChatController::class, 'create']);
Route::get('/chats/{chatId}', [ChatController::class, 'find']);
Route::get('/users/{userId}/chats', [ChatController::class, 'getUserChats']);
