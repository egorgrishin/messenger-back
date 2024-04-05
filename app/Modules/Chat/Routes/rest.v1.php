<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Chat\Controllers\ChatController;

Route::get('/users/{userId}/chats', [ChatController::class, 'getUserChats']);