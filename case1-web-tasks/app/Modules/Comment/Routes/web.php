<?php

use App\Modules\Comment\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])->name('comments.store');
});
