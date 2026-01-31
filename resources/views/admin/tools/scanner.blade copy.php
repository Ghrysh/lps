@extends('layouts.admin')
@section('title', 'Scan QR Code')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">QR Scanner</h2>
        <p class="text-sm text-slate-500">Scan QR Code untuk verifikasi dokumen atau aset LPS</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            {{-- Tab Header --}}
            <div class="flex border-b bg-slate-50/50">
                <button onclick="switchTab('camera')" id="tab-camera"
                    class="flex-1 py-4 text-sm font-bold border-b-2 border-orange-600 text-orange-600 transition">
                    <i class="fas fa-camera mr-2"></i> Kamera Langsung
                </button>
                <button onclick="switchTab('file')" id="tab-file"
                    class="flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-file-import mr-2"></i> Unggah Gambar
                </button>
            </div>

            <div class="p-8">
                <div id="reader-container"
                    class="relative bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 min-h-[320px] flex items-center justify-center overflow-hidden">

                    {{-- QR Engine (Hidden saat mode file) --}}
                    <div id="reader" class="w-full"></div>

                    {{-- Area Unggah Gambar Kustom --}}
                    <div id="file-upload-ui"
                        class="hidden absolute inset-0 bg-slate-50 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 transition"
                        onclick="triggerDefaultFileInput()">
                        <div
                            class="bg-orange-100 text-orange-600 w-20 h-20 rounded-full flex items-center justify-center mb-4 shadow-sm">
                            <i class="fas fa-images text-3xl"></i>
                        </div>
                        <p class="text-slate-800 font-bold">Pilih atau Tarik File Gambar</p>
                        <p class="text-slate-400 text-xs mt-1 italic">Scan otomatis setelah gambar dipilih</p>
                    </div>
                </div>

                {{-- Hasil Scan --}}
                <div id="result"
                    class="mt-8 p-5 bg-white rounded-2xl border-2 border-orange-100 hidden shadow-sm animate-in fade-in zoom-in duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <span
                            class="px-3 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold rounded-full uppercase">Hasil
                            Terdeteksi</span>
                        <button onclick="copyText()" class="text-slate-400 hover:text-orange-600 transition text-sm">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <div id="scannedText" class="text-slate-700 font-mono text-sm break-all leading-relaxed p-2"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode"></script>
        <script>
            let html5QrCode = null; // Inisialisasi null
            const resultContainer = document.getElementById('result');
            const scannedTextContainer = document.getElementById('scannedText');
            const fileUploadUI = document.getElementById('file-upload-ui');

            function onScanSuccess(decodedText) {
                resultContainer.classList.remove('hidden');
                scannedTextContainer.innerText = decodedText;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'QR Code terdeteksi',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            // Fungsi untuk menghentikan kamera dengan aman
            async function stopCamera() {
                if (html5QrCode && html5QrCode.isScanning) {
                    try {
                        await html5QrCode.stop();
                        await html5QrCode.clear();
                    } catch (err) {
                        console.error("Gagal menghentikan kamera:", err);
                    }
                }
            }

            // Inisialisasi & Mulai Kamera
            async function startCamera() {
                // Pastikan kamera bersih sebelum mulai baru
                await stopCamera();

                // Selalu buat instance baru untuk menghindari konflik state
                html5QrCode = new Html5Qrcode("reader");

                const config = {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0
                };

                try {
                    await html5QrCode.start({
                        facingMode: "environment"
                    }, config, onScanSuccess);
                } catch (err) {
                    console.error("Gagal memulai kamera:", err);
                    // Jika gagal (misal: izin ditolak), arahkan ke mode file
                    if (err.includes("Permission denied")) {
                        Swal.fire('Izin Kamera', 'Mohon izinkan akses kamera atau gunakan mode Unggah Gambar.', 'warning');
                    }
                }
            }

            // Proses File Gambar (Gunakan instance terpisah)
            async function handleFileSelect(e) {
                if (e.target.files.length === 0) return;

                const imageFile = e.target.files[0];
                const fileScanner = new Html5Qrcode("reader"); // Instance sementara

                try {
                    const decodedText = await fileScanner.scanFile(imageFile, true);
                    onScanSuccess(decodedText);
                } catch (err) {
                    Swal.fire('Gagal', 'QR Code tidak ditemukan pada gambar ini. Pastikan gambar jelas.', 'error');
                }
            }

            async function switchTab(type) {
                const camTab = document.getElementById('tab-camera');
                const fileTab = document.getElementById('tab-file');

                if (type === 'file') {
                    // Matikan kamera saat pindah ke file
                    await stopCamera();

                    fileTab.classList.add('border-orange-600', 'text-orange-600');
                    fileTab.classList.remove('border-transparent', 'text-slate-400');
                    camTab.classList.remove('border-orange-600', 'text-orange-600');
                    camTab.classList.add('border-transparent', 'text-slate-400');
                    fileUploadUI.classList.remove('hidden');
                    document.getElementById('reader').classList.add('hidden'); // Sembunyikan elemen video
                } else {
                    // Sembunyikan UI upload dan nyalakan kamera
                    fileUploadUI.classList.add('hidden');
                    document.getElementById('reader').classList.remove('hidden');

                    camTab.classList.add('border-orange-600', 'text-orange-600');
                    camTab.classList.remove('border-transparent', 'text-slate-400');
                    fileTab.classList.remove('border-orange-600', 'text-orange-600');
                    fileTab.classList.add('border-transparent', 'text-slate-400');

                    await startCamera();
                }
            }

            function triggerDefaultFileInput() {
                document.getElementById('qr-input-file').click();
            }

            function copyText() {
                navigator.clipboard.writeText(scannedTextContainer.innerText);
                Swal.fire({
                    icon: 'success',
                    title: 'Disalin!',
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            // Setup input file tersembunyi
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'file';
            hiddenInput.id = 'qr-input-file';
            hiddenInput.accept = 'image/*';
            hiddenInput.style.display = 'none';
            hiddenInput.onchange = handleFileSelect;
            document.body.appendChild(hiddenInput);

            // Jalankan kamera saat pertama kali load
            document.addEventListener('DOMContentLoaded', startCamera);
        </script>
    @endpush

    <style>
        /* Sembunyikan semua UI bawaan library agar bersih */
        #reader__dashboard_section,
        #reader__header_message {
            display: none !important;
        }

        #reader {
            border: none !important;
        }

        video {
            border-radius: 1rem !important;
        }
    </style>
@endsection
