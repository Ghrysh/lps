@extends('layouts.admin')
@section('content')
    <div class="max-w-4xl mx-auto p-4">
        <div class="relative rounded-3xl overflow-hidden bg-black shadow-2xl h-[600px] border-4 border-slate-800">
            <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
            <canvas id="canvas" class="hidden"></canvas>

            <div id="ar-container" class="absolute inset-0 pointer-events-none z-50">
                <img id="ar-pinned-image" src="" class="absolute hidden opacity-90 transition-all duration-150">
            </div>

            <div id="scanner-line"
                class="absolute top-0 left-0 w-full h-1 bg-orange-500 shadow-[0_0_15px_#fb923c] animate-scan"></div>
        </div>
    </div>

    <style>
        @keyframes scan {
            0% {
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

        .animate-scan {
            animation: scan 3s infinite ease-in-out;
        }

        #ar-pinned-image {
            position: absolute;
            top: 0;
            left: 0;
            transform-origin: 0 0;
            width: 550px !important;
            /* Ukuran maksimal container AR */
            height: auto !important;
            pointer-events: none;
            z-index: 100;
            border-radius: 15px;
            /* border: 3px solid #fb923c; */
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeric/1.2.6/numeric.min.js"></script>

    <script>
        let lastMatrix = null;
        const SMOOTH_FACTOR = 0.15; // makin kecil = makin halus
    </script>

    <script>
        let lastSpokenFile = null;
        let speech = window.speechSynthesis;
    </script>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const pinnedImage = document.getElementById('ar-pinned-image');

        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment'
                }
            })
            .then(stream => {
                video.srcObject = stream;
                video.onloadedmetadata = () => setInterval(scanObject, 400);
            });

        function scanObject() {
            const context = canvas.getContext('2d');
            const scanW = 400;
            const scanH = (video.videoHeight / video.videoWidth) * scanW;

            canvas.width = scanW;
            canvas.height = scanH;
            context.drawImage(video, 0, 0, scanW, scanH);

            canvas.toBlob(blob => {
                const formData = new FormData();
                formData.append('image', blob);

                fetch('http://localhost:5001/recognize', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Panggil detail dari Laravel menggunakan filename yang didapat
                            fetchDetail(data.filename, data.points);
                        } else {
                            pinnedImage.classList.add('hidden');
                            stopSpeech();
                            lastSpokenFile = null;
                        }
                    })
                    .catch(() => {});
            }, 'image/jpeg', 0.6);
        }

        function fetchDetail(filename, points) {
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
                        // res.data.image_url adalah path gambar dari database
                        // res.data.description adalah teks untuk TTS

                        // 1. Update Posisi AR
                        updateAR(points, res.data.image_url);

                        // 2. Jalankan Suara (TTS)
                        speakText(res.data.title, res.data.description, filename);
                    }
                });
        }

        function updateAR(points, imageUrl) {
            // Set Source Image dari data Laravel
            if (pinnedImage.src !== imageUrl) {
                pinnedImage.src = imageUrl;
            }

            const vW = video.videoWidth;
            const vH = video.videoHeight;
            const elW = video.clientWidth;
            const elH = video.clientHeight;

            const ratio = Math.max(elW / vW, elH / vH);
            const offsetX = (elW - vW * ratio) / 2;
            const offsetY = (elH - vH * ratio) / 2;

            const aiScaleX = vW / 400;
            const aiScaleY = vH / ((vH / vW) * 400);

            const dst = points.map(p => ({
                x: (p[0] * aiScaleX) * ratio + offsetX,
                y: (p[1] * aiScaleY) * ratio + offsetY
            }));

            const imgW = pinnedImage.naturalWidth || 500;
            const imgH = pinnedImage.naturalHeight || 500;
            const scaleFactor = 0.9;

            const w = imgW * scaleFactor;
            const h = imgH * scaleFactor;
            const marginX = (imgW - w) / 2;
            const marginY = (imgH - h) / 2;

            const src = [{
                    x: -marginX / scaleFactor,
                    y: -marginY / scaleFactor
                },
                {
                    x: -marginX / scaleFactor,
                    y: (h + marginY) / scaleFactor
                },
                {
                    x: (w + marginX) / scaleFactor,
                    y: (h + marginY) / scaleFactor
                },
                {
                    x: (w + marginX) / scaleFactor,
                    y: -marginY / scaleFactor
                }
            ];

            try {
                let matrix = solvePerspective(src, dst);
                const floatingOffset = -60;
                matrix[13] += floatingOffset;

                matrix = smoothMatrix(lastMatrix, matrix, SMOOTH_FACTOR);
                lastMatrix = matrix;

                pinnedImage.style.transform = `matrix3d(${matrix.join(',')})`;
                pinnedImage.classList.remove('hidden');
                pinnedImage.style.opacity = "1";
            } catch (e) {
                console.error("Matriks Error", e);
            }
        }

        function solvePerspective(src, dst) {
            let p = [];
            for (let i = 0; i < 4; i++) {
                p.push([src[i].x, src[i].y, 1, 0, 0, 0, -dst[i].x * src[i].x, -dst[i].x * src[i].y]);
                p.push([0, 0, 0, src[i].x, src[i].y, 1, -dst[i].y * src[i].x, -dst[i].y * src[i].y]);
            }
            let b = [dst[0].x, dst[0].y, dst[1].x, dst[1].y, dst[2].x, dst[2].y, dst[3].x, dst[3].y];
            let h = numeric.solve(p, b);
            return [h[0], h[3], 0, h[6], h[1], h[4], 0, h[7], 0, 0, 1, 0, h[2], h[5], 0, 1];
        }
    </script>
    <script>
        function speakText(title, description, filename) {
            if (!title && !description) return;

            // 🔒 Jangan ulangi suara untuk objek yang sama
            if (lastSpokenFile === filename) return;
            lastSpokenFile = filename;

            speech.cancel();

            const text = `${title}. ${description}`;

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = "id-ID";
            utterance.rate = 0.95;
            utterance.pitch = 1.1;

            speech.speak(utterance);
        }

        function stopSpeech() {
            if (speech.speaking) {
                speech.cancel();
            }
        }
    </script>
    <script>
        function smoothMatrix(oldM, newM, factor) {
            if (!oldM) return newM;
            return newM.map((v, i) => oldM[i] + (v - oldM[i]) * factor);
        }
    </script>
@endsection
