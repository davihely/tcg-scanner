<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CardScannerController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/scanner', function () {
    return Inertia::render('Scanner');
})->name('scanner.index');

Route::post('/process-card', [CardScannerController::class, 'processImage']);

Route::get('/modelos-gemini', function () {
    $response = Http::withoutVerifying()
        ->get('https://generativelanguage.googleapis.com/v1beta/models?key=' . env('GEMINI_API_KEY'));
        
    return $response->json();
});
require __DIR__.'/auth.php';
