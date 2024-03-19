<?php
declare(strict_types=1);

use Modules\Draft\Controllers\DraftController;
use Illuminate\Support\Facades\Route;

Route::put('/drafts', [DraftController::class, 'createOrUpdateDraft']);
