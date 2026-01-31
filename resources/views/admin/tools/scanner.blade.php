@extends('layouts.admin')
@section('title', 'Object Recognition AR')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">AR Object Scanner</h2>
        <p class="text-sm text-slate-500">Arahkan kamera ke objek aset untuk menampilkan informasi digital secara real-time.
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-900 h-[500px] border-4 border-white">
            {{-- Video Stream --}}
            <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
            <canvas id="canvas" class="hidden"></canvas>

            {{-- Scanning Animation (Indicator) --}}
            <div id="scanner-line"
                class="absolute top-0 left-0 w-full h-1 bg-orange-500/50 shadow-[0_0_15px_rgba(234,88,12,0.8)] z-10"></div>

            {{-- AR Overlay (Tampil jika terdeteksi) --}}
            <div id="ar-overlay"
                class="hidden absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[2px] z-20">
                <div class="bg-white p-6 rounded-3xl shadow-2xl text-center animate-in zoom-in duration-300 max-w-[80%]">
                    <div
                        class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cube text-3xl"></i>
                    </div>
                    <h3 id="obj-title" class="font-bold text-xl text-slate-800 mb-1">Mendeteksi...</h3>
                    <p id="obj-desc" class="text-sm text-slate-500 leading-relaxed"></p>

                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-orange-600">Terverifikasi Sistem
                            LPS</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p id="status-text" class="text-xs text-slate-400 font-mono">Status: Mencari objek...</p>
        </div>
    </div>

    <style>
        #scanner-line {
            animation: scan 3s infinite linear;
        }

        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }
    </style>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const overlay = document.getElementById('ar-overlay');
        const statusText = document.getElementById('status-text');

        // 1. Akses Kamera
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment'
                }
            })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Kamera error:", err);
                statusText.innerText = "Error: Kamera tidak diizinkan";
            });

        // 2. Loop Deteksi ke AI Service (Python)
        setInterval(() => {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0);

                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('image', blob);

                    // Kirim ke Container AI (Port 5000)
                    fetch('http://localhost:5000/recognize', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                statusText.innerText = "Status: Objek dikenali!";
                                fetchDetailFromLaravel(data.filename);
                            } else {
                                statusText.innerText = "Status: Mencari objek...";
                                overlay.classList.add('hidden');
                            }
                        })
                        .catch(err => {
                            statusText.innerText = "Status: AI Service Offline";
                        });
                }, 'image/jpeg', 0.7); // Compression 0.7 agar lebih cepat
            }
        }, 2000); // Scan setiap 2 detik

        // 3. Ambil Data Detail dari Database Laravel
        function fetchDetailFromLaravel(filename) {
            const formData = new FormData();
            formData.append('filename', filename);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('admin.asset.detail') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        overlay.classList.remove('hidden');
                        document.getElementById('obj-title').innerText = res.data.title;
                        document.getElementById('obj-desc').innerText = res.data.description;

                        // Auto hide setelah 5 detik jika tidak terdeteksi lagi
                        clearTimeout(window.hideTimeout);
                        window.hideTimeout = setTimeout(() => {
                            overlay.classList.add('hidden');
                        }, 5000);
                    }
                });
        }
    </script>
@endsection
