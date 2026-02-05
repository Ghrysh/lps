<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\PhotoboothDataset;
use App\Models\PhotoboothBajuClick;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        /* =========================================
           1. CHART DATA (GRAFIK)
           ========================================= */
        $chartTrend = [
            'labels' => ['16 Jan', '18 Jan', '20 Jan', '22 Jan', '24 Jan', '26 Jan', '28 Jan', '30 Jan', '01 Feb', '03 Feb'],
            'data'   => [4, 10, 6, 9, 7, 7, 9, 25, 0, 2]
        ];

        $chartGender = [
            'labels' => ['Laki-laki', 'Perempuan'],
            'data'   => [83, 47], 
        ];

        $chartActivity = [
            'labels' => ['Scan', 'Foto', 'Game', 'Kopi'],
            'data'   => [108, 60, 80, 12],
        ];

        /* =========================================
           2. STATISTIK UTAMA
           ========================================= */
        $stats = [
            'header' => [
                'pengunjung' => 130,
                'scan'       => 108,
                'poin'       => 10367,
                'foto'       => 60,
                'game'       => 80,
                'kopi'       => '12/40',
            ],
            'tabs' => [
                'pengunjung' => ['total' => 130, 'l' => 83, 'p' => 47],
                'game'       => ['total' => 80, 'avg' => 287, 'poin' => 1159],
                'kopi'       => ['total' => 40, 'ditukar' => 12, 'pending' => 0, 'expired' => 28]
            ]
        ];

        /* =========================================
           3. DATA TABEL & DISTRIBUSI
           ========================================= */
        $pengunjung = [
            ['id' => 'visitor_001', 'gender' => 'L', 'poin' => 0,  'daftar' => '03 Feb, 12:23', 'aktif' => '03 Feb, 12:23'],
            ['id' => 'visitor_002', 'gender' => 'P', 'poin' => 50, 'daftar' => '03 Feb, 10:04', 'aktif' => '03 Feb, 11:00'],
            ['id' => 'visitor_003', 'gender' => 'L', 'poin' => 0,  'daftar' => '30 Jan, 09:35', 'aktif' => '30 Jan, 09:35'],
            ['id' => 'visitor_004', 'gender' => 'L', 'poin' => 10, 'daftar' => '30 Jan, 03:38', 'aktif' => '30 Jan, 04:00'],
            ['id' => 'visitor_005', 'gender' => 'L', 'poin' => 0,  'daftar' => '29 Jan, 23:19', 'aktif' => '29 Jan, 23:19'],
        ];

        $clicks = PhotoboothBajuClick::with('dataset')->get();

        // 2. Grouping berdasarkan kata pertama (e.g., "Aceh")
        $grouped = $clicks->groupBy(function ($click) {
            return explode(' ', trim(optional($click->dataset)->title ?? 'Unknown'))[0];
        });

        $totalAllClicks = $clicks->count();
        $colors = ['bg-purple-500', 'bg-blue-500', 'bg-orange-500', 'bg-emerald-500', 'bg-red-500', 'bg-amber-500', 'bg-slate-500'];

        // --- BAGIAN KOSTUM ---
        // Kita simpan referensi warna ke dalam array agar bisa dipanggil nanti
        $regionColorMap = []; 

        $kostum = $grouped->map(function ($items, $nama) use ($totalAllClicks, &$colors, &$regionColorMap) {
            $count = $items->count();
            $currentColor = array_shift($colors) ?? 'bg-slate-500'; 
            $colors[] = $currentColor;

            // Simpan warna untuk wilayah ini
            $regionColorMap[$nama] = $currentColor;

            return [
                'nama'   => $nama,
                'count'  => $count,
                'persen' => $totalAllClicks > 0 ? round(($count / $totalAllClicks) * 100, 1) : 0,
                'color'  => $currentColor,
            ];
        })->values()->sortByDesc('count')->toArray();

        // --- BAGIAN RIWAYAT FOTO ---
        $latestClicks = PhotoboothBajuClick::with('dataset')
            ->orderBy('clicked_at', 'desc')
            ->take(10)
            ->get();

        $riwayatFoto = $latestClicks->map(function ($click) use ($regionColorMap) {
            // Ambil wilayah (kata pertama) untuk mencocokkan warna
            $fullTitle = optional($click->dataset)->title ?? 'Unknown';
            $regionName = explode(' ', trim($fullTitle))[0];
            
            // Ambil warna dari map yang sudah dibuat di atas, default ke slate jika tidak ada
            $assignedColor = $regionColorMap[$regionName] ?? 'bg-slate-500';

            // Logika warna badge berdasarkan gender_mode (tetap dipertahankan untuk badge)
            $style = [
                'Pria'   => ['bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'text' => 'text-blue-600'],
                'Wanita' => ['bg' => 'bg-pink-50', 'border' => 'border-pink-100', 'text' => 'text-pink-600'],
                'Group'  => ['bg' => 'bg-purple-50', 'border' => 'border-purple-100', 'text' => 'text-purple-600'],
            ];

            $mode = $click->gender_mode ?? 'Group';
            $currentStyle = $style[$mode] ?? $style['Group'];

            return [
                'kostum'       => $fullTitle,
                'gender_mode'  => $mode,
                'waktu'        => $click->clicked_at->format('d M Y, H:i'),
                'bg_color'     => $currentStyle['bg'],
                'border_color' => $currentStyle['border'],
                'text_color'   => $currentStyle['text'],
                'dot_color'    => $assignedColor, // SEKARANG SAMA DENGAN WARNA DI DATA KOSTUM
            ];
        })->toArray();

        $games = [
            ['rank' => 1, 'visitor' => '2e75f116...', 'skor' => 549, 'poin' => 18, 'waktu' => '24 Jan, 04:51'],
            ['rank' => 2, 'visitor' => '8f637d7f...', 'skor' => 531, 'poin' => 15, 'waktu' => '18 Jan, 05:11'],
            ['rank' => 3, 'visitor' => '80792f48...', 'skor' => 509, 'poin' => 22, 'waktu' => '16 Jan, 17:39'],
            ['rank' => 4, 'visitor' => 'b2bc5f49...', 'skor' => 503, 'poin' => 22, 'waktu' => '28 Jan, 18:46'],
            ['rank' => 5, 'visitor' => 'ac641f3e...', 'skor' => 500, 'poin' => 14, 'waktu' => '23 Jan, 02:13'],
        ];

        $kopi = [
            ['kode' => 'KOPI-DB0033', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '28 Jan, 15:04', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-960167', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '28 Jan, 13:43', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-A8019A', 'status' => 'Ditukar', 'poin' => 20, 'dibuat' => '25 Jan, 22:22', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-ADB46A', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '25 Jan, 13:30', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-7F2C91', 'status' => 'Ditukar', 'poin' => 20, 'dibuat' => '25 Jan, 10:15', 'exp' => '30 Jan, 05:07'],
            ['kode' => 'KOPI-B3E044', 'status' => 'Expired', 'poin' => 20, 'dibuat' => '24 Jan, 19:48', 'exp' => '30 Jan, 05:07'],
        ];

        return view('admin.dashboard', compact(
            'chartTrend', 'chartGender', 'chartActivity', 'stats',
            'pengunjung', 'kostum', 'riwayatFoto', 'games', 'kopi'
        ));
    }
}