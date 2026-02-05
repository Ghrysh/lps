<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhotoboothDataset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhotoboothDatasetController extends Controller
{
    public function index()
    {
        $images = PhotoboothDataset::latest()->get();

        return view('admin.dataset.photobooth', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('assets/photobooth'), $filename);

        PhotoboothDataset::create([
            'title' => $request->title,
            'image' => $filename,
        ]);

        return back()->with('success', 'Dataset berhasil ditambahkan');
    }
}
