<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Welcome - LPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 min-h-screen flex flex-col items-center justify-center p-6 text-center relative overflow-hidden">
    
    {{-- Animasi Background blobs --}}
    <div class="absolute top-0 left-0 w-64 h-64 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 max-w-sm w-full">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Halo, {{ Auth::guard('visitor')->user()->name ?? 'Visitor' }}!</h1>
            <p class="text-slate-400">Silakan tonton video sambutan di layar</p>
        </div>

        <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/20 mb-8">
            <div class="animate-pulse">
                <p class="text-white text-sm font-medium">Video sedang diputar...</p>
            </div>
        </div>

        <form action="{{ route('visitor.video.finish') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-white text-slate-900 font-bold py-4 rounded-2xl shadow-xl hover:bg-slate-100 transition active:scale-95 flex items-center justify-center gap-2">
                <span>Selesai Menonton Video</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
    </div>
</body>
</html>