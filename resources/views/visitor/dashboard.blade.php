@extends('layouts.visitor')

@section('title', 'Dashboard')

@section('content')

    {{-- Profile Card --}}
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
        <div
            class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl font-bold text-slate-600 uppercase border-2 border-white shadow-sm">
            {{ substr($visitor->name ?? 'V', 0, 1) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">{{ $visitor->name ?? 'Pengunjung' }}</h2>
            <p class="text-slate-400 text-sm">Visitor</p>
        </div>
    </div>

    {{-- Quick Action Grid --}}
    <div class="grid grid-cols-1 gap-4">

        {{-- Banner QR (Paling Besar) --}}
        <a href="{{ route('visitor.scan.qr') }}"
            class="block bg-gradient-to-r from-orange-500 to-orange-600 p-6 rounded-3xl text-white shadow-lg shadow-orange-200 relative overflow-hidden group active:scale-95 transition-transform">
            <div
                class="absolute right-[-20px] top-[-20px] w-32 h-32 bg-white/10 rounded-full group-hover:scale-110 transition">
            </div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Scan QR Point</h3>
                    <p class="text-orange-100 text-xs mt-0.5">Kumpulkan poin di setiap panel.</p>
                </div>
            </div>
        </a>

        {{-- Grid 2 Kolom untuk menu lainnya --}}
        <div class="grid grid-cols-2 gap-4">

            <a href="{{ route('visitor.scan.ar') }}"
                class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 active:scale-95 transition hover:border-blue-300 group">
                <div
                    class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-3 group-hover:bg-blue-500 group-hover:text-white transition">
                    <i class="fas fa-cube"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">AR Scanner</h3>
                <p class="text-slate-400 text-[10px] mt-1">Informasi Objek teknologi AR</p>
            </a>

            {{-- Tombol Map (Shortcut) --}}
            <a href="{{ route('visitor.map') }}"
                class="block bg-white p-5 rounded-3xl shadow-sm border border-slate-100 active:scale-95 transition hover:border-emerald-300 group w-full text-left">

                <div
                    class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mb-3 group-hover:bg-emerald-500 group-hover:text-white transition">
                    <i class="fas fa-map"></i>
                </div>

                <h3 class="font-bold text-slate-800 text-sm">Peta Lokasi</h3>
                <p class="text-slate-400 text-[10px] mt-1">Lihat Denah Panel</p>
            </a>

        </div>

    </div>

    {{-- Info Banner (Opsional) --}}
    <div class="bg-slate-100 p-4 rounded-2xl flex items-start gap-3">
        <i class="fas fa-info-circle text-slate-400 mt-1"></i>
        <p class="text-slate-500 text-xs leading-relaxed">
            Gunakan tombol <strong>QR</strong> di menu bawah untuk akses cepat memindai poin di lokasi labirin.
        </p>
    </div>

@endsection
