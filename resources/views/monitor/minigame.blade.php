<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Minigame - LPS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="min-h-screen flex flex-col justify-center items-center">

        <div
            class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl sm:rounded-2xl border-t-4 border-orange-500 text-center">

            <h2 class="text-3xl font-bold text-gray-800 mb-2">
                Scan untuk Mulai Minigame 🎮
            </h2>

            <p class="text-gray-500 mb-6">
                Gunakan HP Anda untuk menghubungkan ke permainan
            </p>

            <div class="flex justify-center mb-6">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(260)->generate(
                    url('/visitor/scan-qr?type=minigame&token=' . $token),
                ) !!}
            </div>


            <p class="text-sm text-gray-400">
                Setelah scan, permainan akan dimulai otomatis
            </p>

            <div class="mt-6 flex items-center justify-center gap-2 text-orange-500">
                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                        fill="none" />
                </svg>
                <span class="text-sm font-medium">Menunggu pemain terhubung...</span>
            </div>
        </div>

    </div>

    <script>
        setInterval(() => {
            fetch('/minigame/status/{{ $token }}')
                .then(r => r.json())
                .then(d => {
                    console.log('STATUS:', d.status);

                    if (d.status === 'logged_in') {
                        window.location.href = '/minigame/play/{{ $token }}';
                    }
                });
        }, 1000);
    </script>

</body>

</html>
