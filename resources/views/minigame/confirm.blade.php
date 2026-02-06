<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Mini Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm text-center">
        <h1 class="text-xl font-bold mb-2">
            🎮 Mini Game
        </h1>

        <p class="text-gray-600 mb-6">
            Klik tombol di bawah jika mini game di layar utama sudah selesai
        </p>

        <form method="POST" action="{{ route('minigame.finish', $token) }}">
            @csrf
            <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                Saya Sudah Menyelesaikan
            </button>
        </form>

        <p class="mt-4 text-xs text-gray-400">
            Silakan tutup halaman ini setelah selesai
        </p>
    </div>

</body>

</html>
