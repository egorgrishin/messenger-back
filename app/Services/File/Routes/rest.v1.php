<?php
declare(strict_types=1);

use App\Services\File\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('/files', [FileController::class, 'create']);
