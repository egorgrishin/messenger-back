<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
