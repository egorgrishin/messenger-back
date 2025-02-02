<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Services\Auth\Controllers\AuthController;

Route::post('/access', [AuthController::class, 'access']);
Route::post('/refresh', [AuthController::class, 'refresh']);
