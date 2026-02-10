@extends('layouts.admin')

@section('title', 'Kelola Slider Monitor')

@section('content')
    {{-- Menampilkan Pesan Sukses jika ada --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Container Utama --}}
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Materi Slider</h1>
                <p class="text-slate-400 text-sm">Upload gambar untuk tampilan monitor portrait/landscape</p>
            </div>
            
            <a href="{{ route('news.slider') }}" target="_blank" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium bg-blue-50 px-4 py-2 rounded-lg transition">
                <i class="fas fa-external-link-alt"></i> 
                <span>Preview Tampilan</span>
            </a>
        </div>

        {{-- Form Upload --}}
        <form action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-slate-50 p-6 rounded-xl border border-dashed border-slate-300 mb-8">
            @csrf
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full">
                    <label class="block text-sm font-bold mb-2 text-slate-700">Upload Gambar Baru</label>
                    <input type="file" name="image" class="w-full bg-white border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-emerald-200 outline-none transition"
                        required accept="image/*">
                    <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                        <i class="fas fa-info-circle"></i> Sistem otomatis mendeteksi orientasi (Portrait/Landscape).
                    </p>
                </div>
                <button type="submit"
                    class="w-full md:w-auto bg-emerald-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 active:scale-95">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Upload
                </button>
            </div>
        </form>

        {{-- Divider --}}
        <div class="border-t border-slate-100 my-8"></div>

        {{-- List Gambar --}}
        <h3 class="text-lg font-bold text-slate-700 mb-4">Galeri Aktif</h3>
        
        @if($sliders->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($sliders as $item)
                    <div class="relative group bg-slate-100 rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition">
                        {{-- Image Wrapper (Aspect Ratio 9:16 for Portrait look) --}}
                        <div class="aspect-[9/16] relative">
                            <img src="{{ asset($item->image_path) }}" class="w-full h-full object-cover">
                            
                            {{-- Overlay Gradient --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-60"></div>

                            {{-- Label Tipe --}}
                            <div class="absolute bottom-2 left-2">
                                <span class="text-[10px] font-bold uppercase px-2 py-1 rounded text-white {{ $item->type == 'landscape' ? 'bg-purple-500' : 'bg-emerald-500' }}">
                                    {{ $item->type }}
                                </span>
                            </div>
                        </div>

                        {{-- Delete Button (Hover) --}}
                        <form action="{{ route('admin.slider.destroy', $item->id) }}" method="POST"
                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="bg-white text-red-500 w-8 h-8 rounded-full flex items-center justify-center shadow-lg hover:bg-red-500 hover:text-white transition"
                                onclick="return confirm('Hapus gambar ini?')" title="Hapus Gambar">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                        
                        {{-- Urutan Badge (Optional) --}}
                        <div class="absolute top-2 left-2 bg-black/50 text-white text-[10px] px-2 py-0.5 rounded-full backdrop-blur-sm">
                            #{{ $loop->iteration }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-slate-50 rounded-xl border border-slate-200 border-dashed">
                <div class="inline-block p-4 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <i class="fas fa-images text-3xl"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada gambar slider.</p>
                <p class="text-xs text-slate-400">Silakan upload gambar di atas.</p>
            </div>
        @endif
    </div>
@endsection