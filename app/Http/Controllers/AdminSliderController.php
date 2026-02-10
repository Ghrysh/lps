<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminSliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        // GANTI KE PATH YANG BENAR:
        return view('admin.tools.slider', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480', // Max 20MB
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->extension();
            $path = public_path('uploads/slider');

            if (!File::exists($path)) File::makeDirectory($path, 0777, true);

            $image->move($path, $imageName);
            $fullPath = 'uploads/slider/' . $imageName;

            // Cek Dimensi untuk tentukan Tipe
            list($width, $height) = getimagesize(public_path($fullPath));
            $type = ($width > $height) ? 'landscape' : 'portrait';

            Slider::create([
                'image_path' => $fullPath,
                'type' => $type,
                'order' => Slider::max('order') + 1
            ]);
        }

        return redirect()->back()->with('success', 'Slide berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $slider = Slider::find($id);
        if (File::exists(public_path($slider->image_path))) {
            File::delete(public_path($slider->image_path));
        }
        $slider->delete();
        return redirect()->back()->with('success', 'Slide dihapus.');
    }
}
