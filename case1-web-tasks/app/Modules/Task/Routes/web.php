<?php

use App\Modules\Task\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::get('/kanban', [TaskController::class, 'kanban'])->name('kanban');
    Route::patch('/{task}/status', [TaskController::class, 'updateStatus'])->name('status');
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
});
