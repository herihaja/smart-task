<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskAIController;
use App\Http\Controllers\TaskController as ApiTaskController;
use App\Http\Controllers\Web\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Tasks/Index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/create', [TaskController::class, 'create']);
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit']);
});

Route::middleware(['auth'])->get('/api/debug-auth', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'bearer' => $request->bearerToken(),
        'session_id' => $request->session()->getId(),
        'cookies' => $request->cookies->all(),
        'user' => $request->user()?->only('id', 'email'),
    ]);
});

Route::prefix('api/v0')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', ApiTaskController::class);
    Route::post('/tasks/ai/infer-score', [TaskAIController::class, 'inferScore'])->middleware('throttle:10,1');
});

require __DIR__.'/auth.php';
