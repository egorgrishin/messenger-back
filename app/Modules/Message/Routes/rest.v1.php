<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Message\Controllers\MessageController;

Route::post('/messages', [MessageController::class, 'create']);
Route::get('/chats/{chatId}/messages', [MessageController::class, 'getChatMessages']);
