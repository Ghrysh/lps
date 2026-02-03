<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Data Dummy untuk Tabel Pengunjung
        $pengunjung = [
            ['id' => 'visitor_001', 'gender' => 'L', 'poin' => 0, 'daftar' => '03 Feb, 12:23', 'aktif' => '03 Feb, 12:23'],
            ['id' => 'visitor_002', 'gender' => 'P', 'poin' => 50, 'daftar' => '03 Feb, 10:04', 'aktif' => '03 Feb, 11:00'],
            ['id' => 'visitor_003', 'gender' => 'L', 'poin' => 0, 'daftar' => '30 Jan, 09:35', 'aktif' => '30 Jan, 09:35'],
            ['id' => 'visitor_004', 'gender' => 'L', 'poin' => 10, 'daftar' => '30 Jan, 03:38', 'aktif' => '30 Jan, 04:00'],
            ['id' => 'visitor_005', 'gender' => 'L', 'poin' => 0, 'daftar' => '29 Jan, 23:19', 'aktif' => '29 Jan, 23:19'],
        ];

        // Data Dummy untuk Foto Booth
        $kostum = [
            ['nama' => 'Sulawesi', 'count' => 12, 'persen' => 20.0, 'color' => 'bg-purple-500'],
            ['nama' => 'Sumatera', 'count' => 10, 'persen' => 16.7, 'color' => 'bg-blue-500'],
            ['nama' => 'Kalimantan', 'count' => 10, 'persen' => 16.7, 'color' => 'bg-orange-500'],
            ['nama' => 'Bali', 'count' => 10, 'persen' => 16.7, 'color' => 'bg-emerald-500'],
        ];

        // Data Dummy Game
        $games = [
            ['rank' => 1, 'visitor' => '2e75f116...', 'skor' => 549, 'poin' => 18, 'waktu' => '24 Jan, 04:51'],
            ['rank' => 2, 'visitor' => '8f637d7f...', 'skor' => 531, 'poin' => 15, 'waktu' => '18 Jan, 05:11'],
            ['rank' => 3, 'visitor' => '80792f48...', 'skor' => 509, 'poin' => 22, 'waktu' => '16 Jan, 17:39'],
        ];

        // Data Dummy Kopi
        $kopi = [
            ['kode' => 'KOPI-DB0033', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '28 Jan, 15:04', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-960167', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '28 Jan, 13:43', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-A8019A', 'status' => 'Ditukar', 'poin' => 20, 'dibuat' => '25 Jan, 22:22', 'exp' => '30 Jan, 05:07'],
        ];

        return view('admin.analytics', compact('pengunjung', 'kostum', 'games', 'kopi'));
    }
}