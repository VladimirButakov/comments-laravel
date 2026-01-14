<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\VideoPostController;
use App\Http\Controllers\Api\V1\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // General API info
    Route::get('/', [ApiController::class, 'index'])->name('api.index');

    // Users routes
    Route::apiResource('users', UserController::class)->only(['index', 'show']);

    // News routes
    Route::apiResource('news', NewsController::class);

    // Video posts routes
    Route::apiResource('video-posts', VideoPostController::class);

    // Comments routes
    Route::apiResource('comments', CommentController::class)->except(['index']);
});
