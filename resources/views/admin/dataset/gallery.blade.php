@extends('layouts.admin')
@section('title', 'Galeri LPS')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Image Gallery</h2>
            <p class="text-sm text-slate-500">Dataset Scan AR</p>
        </div>
        <button onclick="document.getElementById('modalUpload').classList.remove('hidden')"
            class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
            <i class="fas fa-plus mr-2"></i> Upload Gambar
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($images as $img)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm group">
                <div class="relative overflow-hidden">
                    {{-- Menggunakan Accessor image_url --}}
                    <img src="{{ $img->image_url }}"
                        class="w-full h-48 object-cover group-hover:scale-110 transition duration-300">
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-slate-800">{{ $img->title }}</h4>
                    <p class="text-[11px] text-slate-500 line-clamp-2">{{ $img->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal Simple --}}
    <div id="modalUpload" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-2xl w-full max-w-md">
            <h3 class="font-bold text-lg mb-4">Upload ke Galeri</h3>
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" placeholder="Judul Gambar" class="w-full p-2 border rounded-lg">
                    <textarea name="description" placeholder="Keterangan singkat" class="w-full p-2 border rounded-lg"></textarea>
                    <input type="file" name="image" class="w-full text-sm">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="this.closest('#modalUpload').classList.add('hidden')"
                            class="px-4 py-2 text-slate-500">Batal</button>
                        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
