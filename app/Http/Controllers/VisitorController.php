<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VisitorController extends Controller
{

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

    public function scanAR()
    {
        return view('visitor.scan-ar');
    }

    public function scan()
    {
        return view('visitor.scan-qr'); // kamera QR
    }


    public function scanQR(Request $request)
    {
        $type  = $request->query('type');
        $token = $request->query('token');
        $id    = $request->query('id');

        if (! $type) {
            abort(404, 'QR tidak valid');
        }

        switch ($type) {

            case 'login':
                return redirect()->route('visitor.login', $token);

            case 'minigame':
                return redirect('/minigame/scan/' . $token);

            case 'asset':
                return response()->json([
                    'asset_id' => $id,
                    'message'  => 'Asset ditemukan'
                ]);

            case 'map':
                return redirect()->route('visitor.map');

            default:
                abort(404, 'QR tidak dikenali');
        }
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
        
        $asset = \App\Models\Gallery::where('image_path', $request->filename)->first();

        if ($asset) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'title' => $asset->title,
                    'description' => $asset->description ?? 'Tidak ada deskripsi',
                    'image_url' => asset('assets/gallery/' . $asset->image_path)
                ]
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Asset tidak ditemukan di database'], 404);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
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
        return redirect()->route('login');
    }
}
