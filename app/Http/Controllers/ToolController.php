<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function gallery()
    {
        $images = Gallery::latest()->get();
        return view('admin.tools.gallery', compact('images'));
    }

    public function storeGallery(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/gallery');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            Gallery::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $filename
            ]);
            return back()->with('success', 'Gambar berhasil disimpan di aset galeri LPS.');
        }

        return back()->with('error', 'Gagal mengunggah gambar.');
    }

    public function scanner()
    {
        $targets = \App\Models\Gallery::all();
        return view('admin.tools.scanner', compact('targets'));
    }

    public function getAssetDetail(Request $request)
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

    public function photobooth()
    {
        $bajuAdat = [
            ['id' => 'jawa', 'name' => 'Adat Jawa', 'description' => 'Solo/Yogyakarta'],
            ['id' => 'bali', 'name' => 'Adat Bali', 'description' => 'Denpasar'],
            ['id' => 'betawi', 'name' => 'Adat Betawi', 'description' => 'Jakarta'],
            ['id' => 'palembang', 'name' => 'Adat Palembang', 'description' => 'Sumatera Selatan'],
            ['id' => 'minangkabau', 'name' => 'Adat Minang', 'description' => 'Sumatera Barat'],
        ];

        return view('admin.tools.photobooth', compact('bajuAdat'));
    }
}
