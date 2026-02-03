@extends('layouts.admin')
@section('title', 'QR Code Point Scanner')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">QR Point Scanner</h2>
        <p class="text-sm text-slate-500">Scan QR Code untuk klaim poin.</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-900 h-[500px] border-4 border-white">
            <div id="reader" class="w-full h-full"></div>

            {{-- Overlay --}}
            <div id="ar-overlay"
                class="hidden absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur z-20">
                <div class="bg-white p-6 rounded-3xl shadow text-center">
                    <i class="fas fa-qrcode text-4xl text-green-600 animate-bounce mb-2"></i>
                    <h3 class="font-bold">QR Terdeteksi</h3>
                    <p class="text-xs text-slate-500">Memproses poin...</p>
                </div>
            </div>
        </div>

        <p id="status-text" class="mt-4 text-xs text-center text-slate-400 italic">
            Status: Menunggu kamera...
        </p>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let html5QrCode;
        let isProcessing = false;
        const overlay = document.getElementById('ar-overlay');
        const statusText = document.getElementById('status-text');

        function speak(text) {
            speechSynthesis.cancel();
            const u = new SpeechSynthesisUtterance(text);
            u.lang = 'id-ID';
            speechSynthesis.speak(u);
        }

        function startScanner() {
            Html5Qrcode.getCameras().then(() => {
                html5QrCode = new Html5Qrcode("reader");
                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    onScanSuccess
                ).then(() => {
                    statusText.innerText = "Status: Arahkan kamera ke QR Code...";
                });
            }).catch(() => {
                statusText.innerText = "Kamera tidak tersedia";
            });
        }

        function onScanSuccess(decodedText) {
            if (isProcessing) return;

            isProcessing = true;
            overlay.classList.remove('hidden');
            statusText.innerText = "Status: Memproses QR...";
            html5QrCode.pause();

            /**
             * FORMAT QR YANG DIDUKUNG:
             * 10
             * POINT:10
             * POINT:10|ASSET:object.png
             */
            let point = 0;
            let filename = null;

            if (decodedText.includes('|')) {
                decodedText.split('|').forEach(item => {
                    if (item.startsWith('POINT:')) point = parseInt(item.replace('POINT:', ''));
                    if (item.startsWith('ASSET:')) filename = item.replace('ASSET:', '');
                });
            } else {
                point = parseInt(decodedText);
            }

            if (!point || isNaN(point)) {
                Swal.fire('QR Tidak Valid', 'QR tidak berisi poin', 'error')
                    .then(resumeScanner);
                return;
            }

            sendPoint(point, filename);
        }

        function sendPoint(point, filename) {
            fetch('{{ route('admin.points.process') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        point_value: point,
                        filename: filename
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        speak(res.data.description);

                        Swal.fire({
                            title: `+${res.points_earned} Poin`,
                            html: `<p class="text-sm">${res.data.description}</p>`,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#ea580c',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // RELOAD HALAMAN OTOMATIS
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Gagal', res.message, 'error').then(() => {
                            window.location.reload();
                        });
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server tidak merespon', 'error')
                        .then(resumeScanner);
                });
        }

        function resumeScanner() {
            isProcessing = false;
            overlay.classList.add('hidden');
            statusText.innerText = "Status: Mencari QR Code...";
            html5QrCode.resume();
        }

        document.addEventListener('DOMContentLoaded', startScanner);
    </script>
@endsection
