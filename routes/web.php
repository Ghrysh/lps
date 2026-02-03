<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Gallery
    Route::get('/gallery', [ToolController::class, 'gallery'])->name('gallery.index');
    Route::post('/gallery', [ToolController::class, 'storeGallery'])->name('gallery.store');

    // Scanner
    Route::get('/scanner', [ToolController::class, 'scanner'])->name('scanner');
    Route::post('/asset-detail', [ToolController::class, 'getAssetDetail'])->name('asset.detail');

    Route::get('/photobooth', [PhotoController::class, 'photobooth'])->name('photobooth');

    // POINT
    Route::get('/scan-points', [PointController::class, 'index'])->name('points.scan');
    // Route untuk memproses point dari QR
    Route::post('/process-points', [PointController::class, 'process'])->name('points.process');
    

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman Game
    Route::get('/tools/minigame', [QuizController::class, 'index'])->name('tools.minigame');

    // Halaman Admin Master Data Soal
    Route::get('/tools/quiz-manager', [QuizController::class, 'manage'])->name('tools.quiz_manager');
    Route::post('/tools/quiz-manager', [QuizController::class, 'store'])->name('tools.quiz_store');
    Route::delete('/tools/quiz-manager/{id}', [QuizController::class, 'destroy'])->name('tools.quiz_delete');
});

require __DIR__ . '/auth.php';
