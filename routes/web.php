<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LabelController;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('tasks.index');
    })->name('dashboard');

    // Rotas do perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas das tarefas
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::get('tasks-trashed', [TaskController::class, 'trashed'])->name('tasks.trashed');
    Route::patch('tasks/{id}/restore', [TaskController::class, 'restore'])->name('tasks.restore');

    Route::resource('labels', LabelController::class)->only(['index', 'store', 'update', 'destroy']);
});
