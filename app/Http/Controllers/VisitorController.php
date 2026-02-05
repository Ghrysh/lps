<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VisitorController extends Controller
{
    // --- HALAMAN AUTH ---
    public function loginForm()
    {
        if (Auth::check()) return redirect()->route('visitor.index');
        return view('visitor.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate(['name' => 'required', 'phone' => 'required']);

        // Format Email dummy dari No HP
        $email = $request->phone . '@visitor.local';

        // Cari user, atau buat jika belum ada
        $user = User::firstOrCreate(
            ['email' => $email], // Cek berdasarkan ini
            [
                'name' => $request->name,
                'password' => Hash::make($request->phone),
                'role' => 'visitor'
            ]
        );

        // Login user tersebut
        Auth::login($user);

        // LOGIKA PENENTU ARAH:
        // Jika user ini baru saja dibuat di database (User Baru) -> Ke Video
        if ($user->wasRecentlyCreated) {
            return redirect()->route('visitor.video');
        }

        // Jika user ini sudah ada sebelumnya (User Lama) -> Langsung Dashboard
        return redirect()->route('visitor.index');
    }

    public function videoStep()
    {
        if (!Auth::check()) return redirect()->route('visitor.login');
        return view('visitor.video');
    }

    public function videoFinish()
    {
        return redirect()->route('visitor.index');
    }

    // --- FITUR UTAMA ---
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('visitor.login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $totalPoints = $user->points()->sum('nilai');

        return view('visitor.dashboard', [
            'totalPoints' => $totalPoints,
            'visitor' => $user
        ]);
    }

    public function scanQR()
    {
        return view('visitor.scan-qr');
    }

    public function scanAR()
    {
        return view('visitor.scan-ar');
    }

    // --- API HANDLER ---

    // [UPDATE PENTING] Menambahkan Validasi Duplikat Pos
    public function apiAddPoint(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Login diperlukan'], 401);
        }

        // Validasi Input
        $request->validate([
            'point_value' => 'required|integer',
            'pos_id'      => 'required|integer' // Wajib kirim ID Pos
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. CEK DUPLIKAT: Apakah user sudah scan POS ini?
        $alreadyScanned = $user->points()
            ->where('keterangan', 'POS ' . $request->pos_id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code ini sudah pernah Anda scan!'
            ]);
        }

        // 2. Simpan Poin Baru jika belum ada
        Point::create([
            'user_id' => $user->id,
            'nilai'   => $request->point_value,
            'keterangan' => 'POS ' . $request->pos_id // Simpan tanda POS
        ]);

        $total = $user->points()->sum('nilai');

        return response()->json([
            'status' => 'success',
            'points_earned' => $request->point_value,
            'total_points' => $total,
            'message' => 'Poin berhasil ditambahkan!'
        ]);
    }

    public function apiAssetDetail(Request $request)
    {
        // Logic AR: Mengembalikan data dummy/database aset
        // Idealnya Anda query ke DB Asset berdasarkan $request->filename

        return response()->json([
            'status' => 'success',
            'data' => [
                'title' => 'Objek Terdeteksi',
                'description' => 'Informasi detail mengenai objek ini ditampilkan melalui Augmented Reality.',
                // Pastikan gambar ini ada di public/assets/
                'image_url' => asset('assets/ar-overlay-example.png')
            ]
        ]);
    }

    public function map()
    {
        if (!Auth::check()) return redirect()->route('visitor.login');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $totalPoints = $user->points()->sum('nilai');

        // Target: 10 Pos x 10 Poin + 50 Poin Minigame = 150
        $maxPoints = 150;
        $isFinished = $totalPoints >= $maxPoints;

        return view('visitor.map', compact('totalPoints', 'maxPoints', 'isFinished'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('visitor.login');
    }
}
