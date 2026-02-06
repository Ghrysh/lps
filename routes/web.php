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

use App\Http\Controllers\VisitorController;

use App\Http\Controllers\QrLoginController;
use App\Http\Controllers\QrMinigameController;


Route::get('/', function () {
    return redirect()->route('login');
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

// ── GROUP VISITOR ──
Route::prefix('visitor')->name('visitor.')->group(function () {
    // Auth & Halaman
    Route::get('/login/{token?}', [QrLoginController::class, 'scanForm'])->name('login'); // token opsional
    Route::post('/login/{token?}', [QrLoginController::class, 'loginPost'])->name('login.post');

    Route::get('/video', [QrLoginController::class, 'videoStep'])->name('video');
    Route::post('/video/finish/{token}', [QrLoginController::class, 'finish'])
        ->name('video.finish');
    // Fitur Utama
    Route::get('/', [VisitorController::class, 'index'])->name('index');
    
    Route::get('/scan', [VisitorController::class, 'scan'])
        ->name('scan');          // halaman scanner

    Route::get('/scan-qr', [VisitorController::class, 'scanQR'])
        ->name('scan.qr');  

    Route::get('/scan-ar', [VisitorController::class, 'scanAR'])->name('scan.ar');

    // ── API KHUSUS VISITOR (PENTING!) ──
    // Ini agar Visitor bisa scan tanpa harus login sebagai Admin
    Route::post('/api/add-point', [VisitorController::class, 'apiAddPoint'])->name('api.point');
    Route::post('/api/asset-detail', [VisitorController::class, 'apiAssetDetail'])->name('api.asset');

    Route::get('/map', [VisitorController::class, 'map'])->name('map');

    Route::post('/logout', [VisitorController::class, 'logout'])->name('logout');
});

Route::prefix('minigame')->name('minigame.')->group(function () {

    // MONITOR
    Route::get('/qr', [QrMinigameController::class, 'index'])->name('qr');
    Route::get('/status/{token}', [QrMinigameController::class, 'status'])->name('status');
    Route::get('/play/{token}', [QrMinigameController::class, 'play'])->name('play');

    // HP
    Route::get('/scan/{token}', [QrMinigameController::class, 'scan'])->name('scan');
    Route::post('/connect/{token}', [QrMinigameController::class, 'connect'])->name('connect');
    Route::post('/finish/{token}', [QrMinigameController::class, 'finish'])->name('finish');
});


Route::get('/monitor', [QrLoginController::class, 'index'])->name('monitor.qr');
Route::get('/monitor/status/{token}', [QrLoginController::class, 'status']);
Route::get('/monitor/play/{token}', [QrLoginController::class, 'play']);




require __DIR__ . '/auth.php';
