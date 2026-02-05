<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PhotoboothDatasetController;
use App\Http\Controllers\PhotoboothBajuClickController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Gallery
    Route::get('/gallery', [ToolController::class, 'gallery'])->name('gallery.index');
    Route::post('/gallery', [ToolController::class, 'storeGallery'])->name('gallery.store');

    Route::get('/photobooth-dataset', [PhotoboothDatasetController::class, 'index'])
        ->name('photobooth.index');

    Route::post('/photobooth-dataset', [PhotoboothDatasetController::class, 'store'])
        ->name('photobooth.store');

    // Scanner
    Route::get('/scanner', [ToolController::class, 'scanner'])->name('scanner');
    Route::post('/asset-detail', [ToolController::class, 'getAssetDetail'])->name('asset.detail');

    // POINT
    Route::get('/scan-points', [PointController::class, 'index'])->name('points.scan');
    // Route untuk memproses point dari QR
    Route::post('/process-points', [PointController::class, 'process'])->name('points.process');
    

});

Route::middleware('auth')->group(function () {
    
    // 1. Minigame Standalone
    Route::get('/minigame', [QuizController::class, 'index'])->name('minigame');

    Route::post('/minigame/save', [QuizController::class, 'saveScore'])->name('minigame.save');

    // 2. Photobooth Standalone
    Route::get('/fotobooth', [PhotoController::class, 'photobooth'])->name('photobooth');
    Route::post('/photobooth/baju-click', [PhotoboothBajuClickController::class, 'store'])
    ->name('photobooth.baju.click');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quiz Manager (Tetap Admin Tool tapi di group auth biasa sesuai request sebelumnya)
    Route::get('/tools/quiz-manager', [QuizController::class, 'manage'])->name('tools.quiz_manager');
    Route::post('/tools/quiz-manager', [QuizController::class, 'store'])->name('tools.quiz_store');
    Route::delete('/tools/quiz-manager/{id}', [QuizController::class, 'destroy'])->name('tools.quiz_delete');
});

require __DIR__ . '/auth.php';
