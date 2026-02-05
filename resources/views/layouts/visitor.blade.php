<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LPS Visitor')</title>
    
    {{-- CDN Libraries --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    {{-- SweetAlert untuk Info Map --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .active-nav { color: #ea580c; font-weight: 700; }
        .inactive-nav { color: #94a3b8; font-weight: 500; }
        
        /* Efek Tombol Tengah */
        .qr-floating-btn {
            box-shadow: 0 -4px 10px rgba(0,0,0,0.05), 0 10px 15px -3px rgba(234, 88, 12, 0.4);
        }
    </style>
</head>
<body class="flex flex-col h-screen overflow-hidden bg-slate-50">

    {{-- 1. TOP HEADER --}}
    <header class="bg-white px-6 py-4 shadow-sm flex justify-between items-center z-40 shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center text-white">
                <i class="fas fa-shield-halved text-sm"></i>
            </div>
            <span class="font-bold text-slate-800 tracking-tight">Labirin Edukasi LPS</span>
        </div>
        
        <div class="flex items-center gap-2 bg-orange-50 px-3 py-1.5 rounded-full border border-orange-100">
            <i class="fas fa-coins text-orange-500 text-sm"></i>
            <span class="font-bold text-orange-700 text-sm">
                {{ Auth::check() ? Auth::user()->points()->sum('nilai') : 0 }}
            </span>
        </div>
    </header>

    {{-- 2. MAIN CONTENT --}}
    <main class="flex-grow overflow-y-auto pb-24 p-6 space-y-6">
        @yield('content')
    </main>

    {{-- 3. BOTTOM NAVIGATION --}}
    <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 h-[75px] z-50 rounded-t-2xl shadow-[0_-5px_20px_rgba(0,0,0,0.03)]">
        
        {{-- Grid 5 Kolom --}}
        <div class="grid grid-cols-5 h-full max-w-lg mx-auto relative">
            
            {{-- 1. PROFILE (Kiri Paling Ujung) --}}
            <a href="{{ route('visitor.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('visitor.index') ? 'active-nav' : 'inactive-nav' }}">
                <i class="fas fa-user text-xl mb-0.5"></i>
                <span class="text-[10px]">Profile</span>
            </a>

            {{-- 2. MAP (Kiri Tengah - On Going) --}}
            <button onclick="Swal.fire('Fitur Segera Hadir', 'Peta interaktif sedang dalam pengembangan.', 'info')" class="flex flex-col items-center justify-center gap-1 text-slate-300 hover:text-slate-400 transition relative">
                {{-- Badge Soon --}}
                <span class="absolute top-3 right-2 w-2 h-2 bg-red-400 rounded-full border-2 border-white"></span>
                <i class="fas fa-map text-xl mb-0.5"></i>
                <span class="text-[10px]">Map</span>
            </button>

            {{-- 3. QR SCANNER (Tengah - Lingkaran Besar) --}}
            <div class="relative flex justify-center">
                <a href="{{ route('visitor.scan.qr') }}" class="absolute -top-8 w-16 h-16 bg-gradient-to-b from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white border-[5px] border-slate-50 qr-floating-btn transform active:scale-95 transition-all">
                    <i class="fas fa-qrcode text-2xl"></i>
                </a>
                <span class="absolute bottom-3 text-[10px] font-bold text-orange-600">Scan QR</span>
            </div>

            {{-- 4. AR SCANNER (Kanan Tengah) --}}
            <a href="{{ route('visitor.scan.ar') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('visitor.scan.ar') ? 'active-nav' : 'inactive-nav' }}">
                <i class="fas fa-cube text-xl mb-0.5"></i>
                <span class="text-[10px]">AR Scan</span>
            </a>

            {{-- 5. KELUAR (Kanan Paling Ujung) --}}
            <a href="{{ route('visitor.login') }}" class="flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-red-500 transition">
                <i class="fas fa-sign-out-alt text-xl mb-0.5"></i>
                <span class="text-[10px]">Keluar</span>
            </a>

        </div>
    </nav>

</body>
</html>