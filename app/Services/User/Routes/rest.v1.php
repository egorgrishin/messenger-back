<?php
declare(strict_types=1);

use App\Services\User\Controllers\UserAvatarController;
use Illuminate\Support\Facades\Route;
use App\Services\User\Controllers\UserController;

Route::get('/users', [UserController::class, 'get']);
Route::post('/users', [UserController::class, 'create']);
Route::put('/users/{userId}', [UserController::class, 'update']);

Route::put('/users/{userId}/avatar', [UserAvatarController::class, 'update']);
Route::delete('/users/{userId}/avatar', [UserAvatarController::class, 'delete']);

Route::get('/users/{userId}/reset-methods', [UserController::class, 'getPasswordResetMethods']);