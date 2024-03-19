<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Auth\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
