@extends('layouts.visitor')

@section('title', 'Object Scanner AR')

@section('content')
    <style>
        /* CSS Khusus Halaman ini */
        @keyframes scan {

            0%,
            100% {
                top: 10%;
                opacity: 0;
            }

            50% {
                opacity: 1;
            }

            100% {
                top: 90%;
                opacity: 0;
            }
        }

        .scan-line {
            animation: scan 3s infinite linear;
        }
    </style>

    {{-- Container Kamera (Dibuat seperti kartu/frame) --}}
    <div
        class="relative w-full h-[calc(100vh-180px)] bg-black rounded-3xl overflow-hidden shadow-xl border border-slate-200">

        {{-- Video & Canvas --}}
        <video id="video" class="w-full h-full object-cover" autoplay playsinline muted></video>
        <canvas id="canvas" class="hidden"></canvas>

        {{-- AR Overlay UI --}}
        <div class="absolute inset-0 pointer-events-none z-10">
            {{-- Garis Scan Biru --}}
            <div class="absolute top-0 left-0 w-full h-1 bg-blue-500 shadow-[0_0_15px_#3b82f6] scan-line"></div>

            {{-- Pinned Image (Hasil Scan) --}}
            <img id="ar-image" src=""
                class="absolute hidden transition-all duration-200 rounded-xl shadow-2xl border-2 border-white/20 backdrop-blur-sm bg-white/10"
                style="max-width: 200px;">
        </div>

        {{-- Status Text --}}
        <div class="absolute bottom-6 w-full text-center pointer-events-none z-20">
            <span class="bg-black/60 text-white px-4 py-2 rounded-full text-xs backdrop-blur-md border border-white/10">
                <i class="fas fa-cube mr-1"></i> Scanning Object...
            </span>
        </div>
    </div>

    {{-- Javascript Logic --}}
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const arImage = document.getElementById('ar-image');

        // Pastikan CSRF Token diambil dengan benar
        const csrfToken = "{{ csrf_token() }}";

        let isScanning = false;
        let speech = window.speechSynthesis;
        let lastSpoken = null;

        // 1. Start Camera
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment'
                }
            }).then(stream => {
                video.srcObject = stream;
                // Mulai loop scan yang aman (Recursive)
                requestAnimationFrame(scanLoop);
            }).catch(err => {
                Swal.fire('Izin Kamera Ditolak', 'Mohon aktifkan izin kamera untuk menggunakan fitur ini.',
                'error');
            });
        }

        // 2. Loop Scan (Aman dari 504 Gateway Time-out)
        function scanLoop() {
            if (video.readyState === video.HAVE_ENOUGH_DATA && !isScanning) {
                processFrame();
            }
            // Cek ulang setiap 500ms agar tidak membebani server Flask
            setTimeout(() => requestAnimationFrame(scanLoop), 500);
        }

        // 3. Process Frame
        function processFrame() {
            isScanning = true;
            const ctx = canvas.getContext('2d');
            const w = 400; // Resize agar ringan dikirim
            const h = (video.videoHeight / video.videoWidth) * w;
            canvas.width = w;
            canvas.height = h;
            ctx.drawImage(video, 0, 0, w, h);

            canvas.toBlob(blob => {
                const formData = new FormData();
                formData.append('image', blob);

                // Kirim ke Flask (Pastikan Service Python berjalan di Port 5001)
                fetch('http://localhost:5001/recognize', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Jika objek dikenali oleh Flask, minta detail ke Laravel
                            fetchAssetDetail(data.filename, data.points);
                        } else {
                            // Jika tidak dikenali, sembunyikan overlay
                            arImage.classList.add('hidden');
                        }
                    })
                    .catch(err => {
                        console.log("Flask error or not connected");
                    })
                    .finally(() => {
                        isScanning = false; // Buka kunci scanning
                    });
            }, 'image/jpeg', 0.6); // Kompresi JPEG 0.6
        }

        // 4. Get Detail from Laravel
        function fetchAssetDetail(filename, points) {
            const formData = new FormData();
            formData.append('filename', filename);
            formData.append('_token', csrfToken);

            fetch('{{ route('visitor.api.asset') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        updateOverlay(points, res.data.image_url);
                        speak(res.data.title, res.data.description, filename);
                    }
                });
        }

        // 5. Update UI Overlay
        function updateOverlay(points, url) {
            arImage.src = url;
            arImage.classList.remove('hidden');

            // Hitung tengah bounding box dari Flask
            // points = [[x1,y1], [x2,y2], [x3,y3], [x4,y4]]
            const cx = (points[0][0] + points[2][0]) / 2;
            const cy = (points[0][1] + points[2][1]) / 2;

            // Scaling koordinat (karena kita resize canvas ke 400px)
            const scaleX = video.clientWidth / 400;

            arImage.style.left = (cx * scaleX) + 'px';
            arImage.style.top = (cy * scaleX) + 'px';
            arImage.style.transform = 'translate(-50%, -50%)';
        }

        // 6. Text to Speech (TTS)
        function speak(title, desc, filename) {
            // Jangan ulangi suara jika objek masih sama
            if (lastSpoken === filename) return;

            lastSpoken = filename;
            speech.cancel(); // Stop suara sebelumnya

            const u = new SpeechSynthesisUtterance(`${title}. ${desc}`);
            u.lang = 'id-ID'; // Bahasa Indonesia
            speech.speak(u);
        }
    </script>
@endsection
