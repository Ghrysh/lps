<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Masuk - Labirin Edukasi</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font & Icons --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Animasi halus untuk input */
        input:focus+label,
        input:not(:placeholder-shown)+label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #ea580c;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">

    {{-- Hiasan Background Abstrak (Opsional) --}}
    <div
        class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-orange-100 rounded-full blur-3xl opacity-50 pointer-events-none">
    </div>
    <div
        class="absolute bottom-[-10%] left-[-10%] w-64 h-64 bg-blue-100 rounded-full blur-3xl opacity-50 pointer-events-none">
    </div>

    <div class="w-full max-w-sm z-10">

        {{-- HEADER & ICON --}}
        <div class="text-center mb-10">
            {{-- Icon Baru --}}
            <div class="relative w-24 h-24 mx-auto mb-6">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl rotate-6 shadow-lg opacity-20">
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-xl flex items-center justify-center text-white text-4xl transform transition hover:-translate-y-1 duration-300">
                    <i class="fas fa-shield-halved"></i>
                </div>
            </div>

            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h1>
            <p class="text-slate-400 mt-2 text-sm">Masuk untuk memulai petualangan di Labirin Edukasi.</p>
        </div>

        {{-- FORM LOGIN --}}
        <form action="{{ route('visitor.login.post', $token) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Input Nama --}}
            <div class="relative group">
                <div class="absolute left-4 top-3.5 text-slate-400 group-focus-within:text-orange-500 transition">
                    <i class="fas fa-user"></i>
                </div>
                <input type="text" name="name" required
                    class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 outline-none transition-all font-semibold text-slate-800 placeholder:text-slate-400"
                    placeholder="Nama Lengkap">
            </div>

            {{-- Input No HP --}}
            <div class="relative group">
                <div class="absolute left-4 top-3.5 text-slate-400 group-focus-within:text-orange-500 transition">
                    <i class="fas fa-phone"></i>
                </div>
                <input type="tel" name="phone" required
                    class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-100 outline-none transition-all font-semibold text-slate-800 placeholder:text-slate-400"
                    placeholder="Nomor WhatsApp / HP">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                    Jenis Kelamin
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="L" required class="accent-orange-600">
                        <span class="text-slate-700">Laki-laki</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="P" required class="accent-orange-600">
                        <span class="text-slate-700">Perempuan</span>
                    </label>
                </div>
            </div>

            {{-- Tombol Login --}}
            <button type="submit"
                class="w-full bg-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl shadow-slate-200 hover:bg-orange-600 hover:shadow-orange-200 transition-all duration-300 transform active:scale-95 flex items-center justify-center gap-2">
                <span>Mulai Sekarang</span>
                <i class="fas fa-arrow-right text-sm"></i>
            </button>
        </form>

        {{-- Footer Kecil --}}
        <div class="mt-10 text-center">
            <p class="text-xs text-slate-400">© 2026 Labirin Edukasi LPS</p>
        </div>

    </div>

</body>

</html>
