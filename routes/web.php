<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class);
Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
Route::get('tasks-trashed', [TaskController::class, 'trashed'])->name('tasks.trashed');
Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
