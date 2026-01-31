<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gallery
    Route::get('/gallery', [ToolController::class, 'gallery'])->name('gallery.index');
    Route::post('/gallery', [ToolController::class, 'storeGallery'])->name('gallery.store');

    // Scanner
    Route::get('/scanner', [ToolController::class, 'scanner'])->name('scanner');
    Route::post('/asset-detail', [ToolController::class, 'getAssetDetail'])->name('asset.detail');

    Route::get('/photobooth', [ToolController::class, 'photobooth'])->name('photobooth');
    
    // Rute master data lainnya...
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
