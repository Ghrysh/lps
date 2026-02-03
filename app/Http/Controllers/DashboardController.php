<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /* =======================
         |  STAT CARDS
         ======================= */
        $stats = [
            'pengunjung' => 130,
            'scan'       => 108,
            'poin'       => 10367,
            'foto'       => 60,
            'game'       => 80,
            'kopi'       => [
                'terpakai' => 12,
                'total'   => 40,
            ],
        ];

        /* =======================
         |  CHART DATA
         ======================= */
        $chartTrend = [
            'labels' => ['25 Jan', '26 Jan', '27 Jan', '28 Jan', '29 Jan', '30 Jan', '31 Jan'],
            'data'   => [10, 18, 25, 22, 30, 15, 10],
        ];

        $chartGender = [
            'labels' => ['Laki-laki', 'Perempuan'],
            'data'   => [83, 47],
        ];

        $chartActivity = [
            'labels' => ['Scan', 'Foto Booth', 'Game', 'Kopi'],
            'data'   => [108, 60, 80, 12],
        ];

        /* =======================
         |  TABLE DATA
         ======================= */
        $pengunjung = [
            ['id' => 'visitor_001', 'gender' => 'L', 'poin' => 0,  'daftar' => '03 Feb, 12:23', 'aktif' => '03 Feb, 12:23'],
            ['id' => 'visitor_002', 'gender' => 'P', 'poin' => 50, 'daftar' => '03 Feb, 10:04', 'aktif' => '03 Feb, 11:00'],
            ['id' => 'visitor_003', 'gender' => 'L', 'poin' => 0,  'daftar' => '30 Jan, 09:35', 'aktif' => '30 Jan, 09:35'],
            ['id' => 'visitor_004', 'gender' => 'L', 'poin' => 10, 'daftar' => '30 Jan, 03:38', 'aktif' => '30 Jan, 04:00'],
        ];

        $kostum = [
            ['nama' => 'Sulawesi', 'count' => 12, 'persen' => 20, 'color' => 'bg-purple-500'],
            ['nama' => 'Sumatera', 'count' => 10, 'persen' => 16.7, 'color' => 'bg-blue-500'],
            ['nama' => 'Kalimantan', 'count' => 10, 'persen' => 16.7, 'color' => 'bg-orange-500'],
            ['nama' => 'Bali', 'count' => 10, 'persen' => 16.7, 'color' => 'bg-emerald-500'],
        ];

        $games = [
            ['rank' => 1, 'visitor' => '2e75f116...', 'skor' => 549, 'poin' => 18, 'waktu' => '24 Jan, 04:51'],
            ['rank' => 2, 'visitor' => '8f637d7f...', 'skor' => 531, 'poin' => 15, 'waktu' => '18 Jan, 05:11'],
            ['rank' => 3, 'visitor' => '80792f48...', 'skor' => 509, 'poin' => 22, 'waktu' => '16 Jan, 17:39'],
        ];

        $kopi = [
            ['kode' => 'KOPI-DB0033', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '28 Jan, 15:04', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-960167', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '28 Jan, 13:43', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-A8019A', 'status' => 'Ditukar', 'poin' => 20, 'dibuat' => '25 Jan, 22:22', 'exp' => '30 Jan, 05:07'],
        ];

        return view('admin.dashboard', compact(
            'stats',
            'chartTrend',
            'chartGender',
            'chartActivity',
            'pengunjung',
            'kostum',
            'games',
            'kopi'
        ));
    }
}
