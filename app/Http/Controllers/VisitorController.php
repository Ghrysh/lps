<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

        // TRIK: Buat User dari No HP
        // Format Email: 08123456@visitor.local
        // Password: 08123456 (Sama dengan No HP)

        $email = $request->phone . '@visitor.local';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->phone),
                'role' => 'visitor' // Pastikan kolom role ada di tabel users (opsional)
            ]
        );

        // Login Pakai Guard Standar 'web'
        Auth::login($user);

        return redirect()->route('visitor.video');
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

        // Tambahkan baris komentar ini (Type Hint) agar editor tidak error
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Sekarang editor tahu $user punya method points()
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

    // --- API HANDLER (User Based) ---
    public function apiAddPoint(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Login diperlukan'], 401);
        }

        $pointValue = $request->point_value;

        // Simpan ke User ID
        Point::create([
            'user_id' => Auth::id(),
            'nilai' => $pointValue
        ]);

        return response()->json([
            'status' => 'success',
            'points_earned' => $pointValue,
            'message' => 'Poin berhasil ditambahkan!'
        ]);
    }

    public function apiAssetDetail(Request $request)
    {
        // Logic AR Asset Detail (Sama seperti sebelumnya)
        return response()->json([
            'status' => 'success',
            'data' => [
                'title' => 'Objek Terdeteksi',
                'description' => 'Informasi objek ditampilkan di sini.',
                'image_url' => asset('assets/ar-overlay-example.png')
            ]
        ]);
    }
}
