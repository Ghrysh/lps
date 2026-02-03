<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function index()
    {
        return view('admin.points.scan');
    }

    public function process(Request $request)
    {
        $request->validate([
            'point_value' => 'required|integer',
            'filename' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Simpan point ke database
            $point = Point::create([
                'user_id' => Auth::id(),
                'nilai' => $request->point_value,
            ]);

            DB::commit();

            // Logika simulasi: Jika Anda juga ingin mengambil detail aset dari filename
            // Anda bisa menggabungkan logika recognition di sini
            return response()->json([
                'status' => 'success',
                'message' => 'Poin berhasil ditambahkan!',
                'points_earned' => $request->point_value,
                // Simulasi data aset untuk UI
                'data' => [
                    'title' => 'Reward Scan',
                    'description' => "Selamat! Anda mendapatkan {$request->point_value} poin dari objek ini.",
                    'image_url' => asset('shared/assets/success-reward.png') 
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}