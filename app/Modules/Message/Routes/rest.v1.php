<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Message\Controllers\MessageController;

Route::post('/messages', [MessageController::class, 'create']);
Route::patch('/messages/{messageId}', [MessageController::class, 'update']);
