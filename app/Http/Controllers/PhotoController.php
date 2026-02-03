<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function photobooth()
    {
        $bajuAdat = [
            // --- SUMATERA ---
            ['id' => 'aceh', 'name' => 'Adat Aceh', 'description' => 'Nanggroe Aceh Darussalam'],
            ['id' => 'sumatra-utara', 'name' => 'Adat Batak', 'description' => 'Sumatera Utara'],
            ['id' => 'minangkabau', 'name' => 'Adat Minang', 'description' => 'Sumatera Barat'],
            ['id' => 'riau', 'name' => 'Adat Riau', 'description' => 'Riau (Indragiri)'],
            ['id' => 'kepulauan-riau', 'name' => 'Adat Kepri', 'description' => 'Kepulauan Riau (Melayu)'],
            ['id' => 'jambi', 'name' => 'Adat Jambi', 'description' => 'Jambi (Melayu)'],
            ['id' => 'bengkulu', 'name' => 'Adat Bengkulu', 'description' => 'Bengkulu'],
            ['id' => 'palembang', 'name' => 'Adat Palembang', 'description' => 'Sumatera Selatan'],
            ['id' => 'bangka-belitung', 'name' => 'Adat Bangka', 'description' => 'Bangka Belitung'],
            ['id' => 'lampung', 'name' => 'Adat Lampung', 'description' => 'Lampung'],

            // --- JAWA ---
            ['id' => 'betawi', 'name' => 'Adat Betawi', 'description' => 'DKI Jakarta'],
            ['id' => 'baduy', 'name' => 'Adat Baduy', 'description' => 'Banten'],
            ['id' => 'sunda', 'name' => 'Adat Sunda', 'description' => 'Jawa Barat'],
            ['id' => 'jawa', 'name' => 'Adat Jawa', 'description' => 'Jawa Tengah (Solo)'],
            ['id' => 'yogyakarta', 'name' => 'Adat Jogja', 'description' => 'DI Yogyakarta'],
            ['id' => 'madura', 'name' => 'Adat Madura', 'description' => 'Jawa Timur'],

            // --- BALI & NUSA TENGGARA ---
            ['id' => 'bali', 'name' => 'Adat Bali', 'description' => 'Bali'],
            ['id' => 'lombok', 'name' => 'Adat Sasak', 'description' => 'NTB (Lombok)'],
            ['id' => 'nusa-tenggara-barat', 'name' => 'Adat NTB', 'description' => 'Nusa Tenggara Barat'],
            ['id' => 'ntt', 'name' => 'Adat NTT', 'description' => 'Nusa Tenggara Timur'],

            // --- KALIMANTAN ---
            ['id' => 'kalimantan', 'name' => 'Adat Kalimantan', 'description' => 'Kalimantan (Umum)'],
            ['id' => 'kalimantan-barat', 'name' => 'Adat Kalbar', 'description' => 'Kalimantan Barat'],
            ['id' => 'kalimantan-timur', 'name' => 'Adat Kaltim', 'description' => 'Kalimantan Timur'],
            ['id' => 'kalimantan-utara', 'name' => 'Adat Kaltara', 'description' => 'Kalimantan Utara'],
            ['id' => 'dayak', 'name' => 'Adat Dayak', 'description' => 'Suku Dayak'],

            // --- SULAWESI ---
            ['id' => 'sulawesi-utara', 'name' => 'Adat Minahasa', 'description' => 'Sulawesi Utara'],
            ['id' => 'gorontalo', 'name' => 'Adat Gorontalo', 'description' => 'Gorontalo'],
            ['id' => 'sulawesi-tengah', 'name' => 'Adat Sulteng', 'description' => 'Sulawesi Tengah'],
            ['id' => 'sulawesi-barat', 'name' => 'Adat Mandar', 'description' => 'Sulawesi Barat'],
            ['id' => 'sulawesi-selatan', 'name' => 'Adat Sulsel', 'description' => 'Sulawesi Selatan'],
            ['id' => 'bugis', 'name' => 'Adat Bugis', 'description' => 'Suku Bugis'],
            ['id' => 'sulawesi-tenggara', 'name' => 'Adat Tolaki', 'description' => 'Sulawesi Tenggara'],

            // --- MALUKU & PAPUA ---
            ['id' => 'maluku', 'name' => 'Adat Maluku', 'description' => 'Maluku'],
            ['id' => 'maluku-utara', 'name' => 'Adat Malut', 'description' => 'Maluku Utara'],
            ['id' => 'papua', 'name' => 'Adat Papua', 'description' => 'Papua'],
            ['id' => 'papua-barat', 'name' => 'Adat Pabar', 'description' => 'Papua Barat'],
            ['id' => 'papua-selatan', 'name' => 'Adat Asmat', 'description' => 'Papua Selatan'],
            ['id' => 'papua-pegunungan', 'name' => 'Adat Dani', 'description' => 'Papua Pegunungan'],
        ];

        usort($bajuAdat, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return view('admin.tools.photobooth', compact('bajuAdat'));
    }
}
