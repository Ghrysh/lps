<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PhotoboothDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'aceh_pria.png',
            'aceh_wanita.png',
            'baduy_pria.png',
            'baduy_wanita.png',
            'bali_pria.png',
            'bali_wanita.png',
            'bangka-belitung_pria.png',
            'bangka-belitung_wanita.png',
            'bengkulu_pria.png',
            'bengkulu_wanita.png',
            'betawi_pria.png',
            'betawi_wanita.png',
            'bugis_pria.png',
            'bugis_wanita.png',
            'dayak_pria.png',
            'dayak_wanita.png',
            'gorontalo_pria.png',
            'gorontalo_wanita.png',
            'jambi_pria.png',
            'jambi_wanita.png',
            'jawa_pria.png',
            'jawa_wanita.png',
            'kalimantan_pria.png',
            'kalimantan_wanita.png',
            'kalimantan-barat_pria.png',
            'kalimantan-barat_wanita.png',
            'kalimantan-timur_pria.png',
        ];

        foreach ($data as $file) {
            $title = str_replace(
                ['_', '-', '.png'],
                [' ', ' ', ''],
                ucfirst($file)
            );

            DB::table('photobooth_datasets')->insert([
                'id' => (string) Str::uuid(),
                'title' => ucwords($title),
                'image' => $file,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
