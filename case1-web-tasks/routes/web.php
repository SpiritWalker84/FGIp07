<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
