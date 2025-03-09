<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Services\Message\Controllers\MessageController;

Route::post('/messages', [MessageController::class, 'create']);
Route::put('/messages/{messageId}', [MessageController::class, 'update']);
Route::delete('/messages/{messageId}', [MessageController::class, 'delete']);
Route::get('/chats/{chatId}/messages', [MessageController::class, 'getChatMessages']);
