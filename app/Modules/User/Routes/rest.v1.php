<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\UserController;

Route::get('/users', [UserController::class, 'get']);
Route::get('/users/{userId}', [UserController::class, 'find']);
Route::post('/users', [UserController::class, 'create']);

Route::get('/users/{userId}/friends', [UserController::class, 'getUserFriends']);
Route::get('/users/{userId}/subscriptions', [UserController::class, 'getUserSubscriptions']);
Route::get('/users/{userId}/subscribers', [UserController::class, 'getUserSubscribers']);

Route::put('/users/{userId}/friends/{friendId}', [UserController::class, 'addUserFriend']);
Route::delete('/users/{userId}/friends/{friendId}', [UserController::class, 'deleteUserFriend']);
