<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Visitor Login - LPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col justify-center p-6">
    <div class="max-w-md mx-auto w-full bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user text-3xl text-orange-600"></i> </div>
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang</h1>
            <p class="text-slate-500 text-sm">Silakan isi data diri untuk masuk</p>
        </div>

        <form action="{{ route('visitor.login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" placeholder="Contoh: Budi Santoso">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Nomor HP / WhatsApp</label>
                <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" placeholder="Contoh: 08123456789">
            </div>
            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:bg-orange-700 transition active:scale-95">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>