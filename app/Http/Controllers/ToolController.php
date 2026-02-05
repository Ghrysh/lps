<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;

class ToolController extends Controller
{
    public function gallery()
    {
        $images = Gallery::latest()->get();
        return view('admin.dataset.gallery', compact('images'));
    }

    public function storeGallery(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        'description' => 'nullable|string'
    ]);

    if ($request->hasFile('image')) {
        $manager = new ImageManager(new Driver());
        $file = $request->file('image');
        $filename = time() . '_infographic.jpg';
        $destinationPath = public_path('assets/gallery');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        /* =========================
         * 1. BACA & BUAT GAMBAR KOTAK
         * ========================= */
        $img = $manager->read($file);

        $size = min($img->width(), $img->height());
        $img->crop($size, $size);        // Crop ke kotak
        $img->scale(height: 650);        // Sedikit lebih besar dari sebelumnya

        $imgWidth = $img->width();
        $imgHeight = $img->height();

        /* =========================
         * 2. SET AREA TEKS
         * ========================= */
        $textAreaWidth = 480;
        $padding = 45;
        $totalWidth = $imgWidth + $textAreaWidth;

        /* =========================
         * 3. BUAT CANVAS
         * ========================= */
        $infographic = $manager
            ->create($totalWidth, $imgHeight)
            ->fill('#ffffff');

        $infographic->place($img, 'top-left');

        $fontBold = public_path('fonts/Roboto-Bold.ttf');
        $fontReg  = public_path('fonts/Roboto-Regular.ttf');

        $textPosX = $imgWidth + $padding;
        $maxTextWidth = $textAreaWidth - ($padding * 2);

        /* =========================
         * 4. JUDUL (CENTER)
         * ========================= */
        $centerX = $imgWidth + ($textAreaWidth / 2);

        $infographic->text($request->title, $centerX, 60, function ($font) use ($fontBold) {
            $font->filename($fontBold);
            $font->size(30);
            $font->color('#1a1a1a');
            $font->align('center');
            $font->valign('top');
        });

        /* =========================
         * 5. DESKRIPSI (PSEUDO-JUSTIFY)
         * ========================= */
        $description = $request->description ?? '';

        // Wrap lebih rapat agar tampak rata kiri-kanan
        $wrappedText = wordwrap($description, 38, "\n", true);

        $infographic->text($wrappedText, $textPosX, 140, function ($font) use ($fontReg) {
            $font->filename($fontReg);
            $font->size(19);              // 🔥 Diperbesar
            $font->color('#444444');
            $font->align('left');
            $font->valign('top');
            $font->lineHeight(1.7);       // Lebih lega & elegan
        });

        /* =========================
         * 6. SIMPAN
         * ========================= */
        $infographic
            ->toJpeg(92)
            ->save($destinationPath . '/' . $filename);

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $filename
        ]);

        return back()->with('success', 'Infografis berhasil dibuat!');
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


}
