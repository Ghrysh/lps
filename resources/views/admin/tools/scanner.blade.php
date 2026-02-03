@extends('layouts.admin')
@section('title', 'Object Recognition AR')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">AR Object Scanner</h2>
        <p class="text-sm text-slate-500">Arahkan kamera ke objek aset untuk mengunduh informasi digital secara real-time.
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-slate-900 h-[500px] border-4 border-white">
            {{-- Video Stream --}}
            <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
            <canvas id="canvas" class="hidden"></canvas>

            {{-- Scanning Animation --}}
            <div id="scanner-line"
                class="absolute top-0 left-0 w-full h-1 bg-orange-500/50 shadow-[0_0_15px_rgba(234,88,12,0.8)] z-10"></div>

            {{-- AR Overlay Mini (Indikator Cepat) --}}
            <div id="ar-overlay"
                class="hidden absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[2px] z-20">
                <div class="bg-white p-6 rounded-3xl shadow-2xl text-center animate-in zoom-in duration-300">
                    <div class="animate-bounce mb-2 text-orange-600">
                        <i class="fas fa-check-circle text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Objek Teridentifikasi</h3>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p id="status-text" class="text-xs text-slate-400 font-mono italic">Status: Menunggu kamera...</p>
        </div>
    </div>

    {{-- Dependencies --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .swal2-html-container {
            margin: 1em 0 0 !important;
        }
    </style>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const overlay = document.getElementById('ar-overlay');
        const statusText = document.getElementById('status-text');

        let isScanning = true;
        let capturedImageBase64 = null;

        // --- FUNGSI TEXT TO SPEECH ---
        function speak(text) {
            // Menghentikan suara yang sedang berjalan agar tidak tumpang tindih
            window.speechSynthesis.cancel();

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID'; // Mengatur bahasa ke Indonesia
            utterance.rate = 1.0;
            window.speechSynthesis.speak(utterance);
        }

        // 1. Akses Kamera
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment'
                }
            })
            .then(stream => {
                video.srcObject = stream;
                statusText.innerText = "Status: Mencari objek...";
                video.onloadedmetadata = () => scanObject();
            })
            .catch(err => {
                statusText.innerText = "Error: Kamera tidak diizinkan";
                console.error(err);
            });

        // 2. Fungsi Scan Utama
        function scanObject() {
            if (!isScanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const context = canvas.getContext('2d');
                canvas.width = 480;
                canvas.height = (video.videoHeight / video.videoWidth) * 480;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const currentFrame = canvas.toDataURL('image/jpeg', 0.7);

                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('image', blob);

                    fetch('http://localhost:5001/recognize', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success' && isScanning) {
                                isScanning = false;
                                capturedImageBase64 = currentFrame;
                                overlay.classList.remove('hidden');
                                fetchDetailFromLaravel(data.filename);
                            } else {
                                if (isScanning) setTimeout(scanObject, 1500);
                            }
                        })
                        .catch(err => {
                            statusText.innerText = "Status: AI Service Offline";
                            if (isScanning) setTimeout(scanObject, 3000);
                        });
                }, 'image/jpeg', 0.6);
            } else {
                setTimeout(scanObject, 500);
            }
        }

        // 3. Ambil Detail dari Laravel
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
                        const asset = res.data;
                        const fullText = `${asset.title}. ${asset.description}`;

                        // Jalankan suara pertama kali
                        speak(fullText);

                        Swal.fire({
                            title: `<span class="text-slate-800">${asset.title}</span>`,
                            html: `
                        <div class="mt-4">
                            <div class="mb-3 text-orange-600 animate-pulse">
                                <i class="fas fa-volume-up"></i> <small class="font-bold">Membacakan Deskripsi...</small>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1 text-center">Kamera</p>
                                    <div class="rounded-xl overflow-hidden border-2 border-orange-500 shadow-sm">
                                        <img src="${capturedImageBase64}" class="w-full h-32 object-cover">
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1 text-center">Real Data</p>
                                    <div class="rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                                        <img src="${asset.image_url}" class="w-full h-32 object-cover" onerror="this.src='/shared/assets/placeholder.png'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `,
                            width: '550px',
                            showCancelButton: true,
                            confirmButtonColor: '#ea580c',
                            confirmButtonText: '<i class="fas fa-redo mr-2"></i> Ulangi Suara',
                            cancelButtonText: 'Lanjut Scan',
                            allowOutsideClick: false,
                            preConfirm: () => {
                                // Mencegah popup tertutup saat klik "Ulangi Suara"
                                speak(fullText);
                                return false;
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.cancel) {
                                window.speechSynthesis.cancel();
                                resumeScanning();
                            }
                        });
                    } else {
                        resumeScanning();
                    }
                })
                .catch(() => {
                    window.speechSynthesis.cancel();
                    resumeScanning();
                });
        }

        // 4. Fungsi Kontrol
        function resumeScanning() {
            isScanning = true;
            overlay.classList.add('hidden');
            statusText.innerText = "Status: Mencari objek...";
            scanObject();
        }
    </script>
@endsection
