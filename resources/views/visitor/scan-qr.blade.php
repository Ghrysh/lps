@extends('layouts.visitor')

@section('title', 'Scan QR Point')

@section('content')
    {{-- Load Library QR Khusus Halaman Ini --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    {{-- Container Scanner --}}
    <div class="relative w-full h-[calc(100vh-180px)] bg-black rounded-3xl overflow-hidden shadow-xl border border-slate-200">
        
        {{-- Area Kamera --}}
        <div id="qr-reader" class="w-full h-full object-cover"></div>
        
        {{-- Overlay Design --}}
        <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
            {{-- Bingkai Fokus --}}
            <div class="w-64 h-64 border-2 border-white/50 rounded-3xl relative">
                <div class="absolute top-0 left-0 w-8 h-8 border-l-4 border-t-4 border-orange-500 rounded-tl-xl"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-r-4 border-t-4 border-orange-500 rounded-tr-xl"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-l-4 border-b-4 border-orange-500 rounded-bl-xl"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-r-4 border-b-4 border-orange-500 rounded-br-xl"></div>
            </div>
            
            {{-- Instruksi --}}
            <p class="text-white mt-8 text-xs bg-black/60 px-4 py-2 rounded-full backdrop-blur-sm border border-white/10">
                Arahkan kamera ke QR Code Poin
            </p>
        </div>
    </div>

    {{-- Javascript Logic --}}
    <script>
        let html5QrCode;
        // Ambil CSRF Token
        const csrfTokenQR = "{{ csrf_token() }}";

        function onScanSuccess(decodedText) {
            html5QrCode.pause(); // Jeda biar gak double scan

            let point = 0;
            // Parsing Format: "POINT:10|ASSET:..." atau hanya "10"
            if (decodedText.includes('|')) {
                decodedText.split('|').forEach(item => {
                    if (item.startsWith('POINT:')) point = parseInt(item.replace('POINT:', ''));
                });
            } else {
                point = parseInt(decodedText);
            }

            if (!point || isNaN(point)) {
                Swal.fire({
                    icon: 'error',
                    title: 'QR Tidak Valid',
                    text: 'QR Code tidak mengandung poin.',
                    confirmButtonColor: '#f97316'
                }).then(() => html5QrCode.resume());
                return;
            }

            // Kirim Point ke API
            fetch('{{ route('visitor.api.point') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfTokenQR,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ point_value: point })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Play Sound
                    let u = new SpeechSynthesisUtterance("Poin berhasil ditambahkan");
                    u.lang = 'id-ID';
                    window.speechSynthesis.speak(u);

                    Swal.fire({
                        icon: 'success',
                        title: `+${data.points_earned} Poin!`,
                        text: data.message,
                        confirmButtonText: 'Lanjut',
                        confirmButtonColor: '#f97316'
                    }).then(() => {
                        window.location.href = "{{ route('visitor.index') }}"; // Balik ke dashboard
                    });
                } else {
                    Swal.fire('Info', data.message, 'info').then(() => html5QrCode.resume());
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal memproses', 'error').then(() => html5QrCode.resume());
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).catch(err => {
                Swal.fire('Kamera Error', 'Pastikan izin kamera aktif di browser.', 'error');
            });
        });
    </script>
@endsection