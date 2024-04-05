<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\UserController;

Route::get('/users', [UserController::class, 'get']);
Route::get('/users/{userId}', [UserController::class, 'find']);
Route::post('/users', [UserController::class, 'create']);
