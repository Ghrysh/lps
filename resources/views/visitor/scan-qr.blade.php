@extends('layouts.visitor')

@section('title', 'Scan QR Point')

@section('content')
    {{-- Load Library QR Khusus Halaman Ini --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    {{-- Container Scanner --}}
    <div
        class="relative w-full h-[calc(100vh-180px)] bg-black rounded-3xl overflow-hidden shadow-xl border border-slate-200">

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

        function onScanSuccess(decodedText) {
            html5QrCode.pause(); // stop biar gak double

            // Validasi harus URL
            if (!decodedText.startsWith('http')) {
                Swal.fire({
                    icon: 'error',
                    title: 'QR Tidak Valid',
                    text: 'QR Code tidak dikenali sistem.',
                    confirmButtonColor: '#f97316'
                }).then(() => html5QrCode.resume());
                return;
            }

            // Redirect ke URL QR
            window.location.href = decodedText;
        }

        document.addEventListener("DOMContentLoaded", () => {
            html5QrCode = new Html5Qrcode("qr-reader");
            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                onScanSuccess
            ).catch(() => {
                Swal.fire(
                    'Kamera Error',
                    'Pastikan izin kamera aktif di browser.',
                    'error'
                );
            });
        });
    </script>

@endsection
