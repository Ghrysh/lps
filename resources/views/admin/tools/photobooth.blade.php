@extends('layouts.admin')
@section('title', 'AI Photobooth')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">AI Photobooth</h2>
        <p class="text-sm text-slate-500">Virtual Try-On Baju Adat Indonesia</p>
    </div>
    <div class="space-x-2">
        <button onclick="resetSetup()" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300 transition shadow-sm">
            <i class="fas fa-sync mr-2"></i> Reset Pilihan
        </button>
        <button onclick="capturePhoto()" id="btnCapture" class="hidden bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-900 transition shadow-lg">
            <i class="fas fa-print mr-2"></i> Cetak Foto
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    {{-- Area Kamera / Canvas --}}
    <div class="lg:col-span-3">
        <div class="relative bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border-4 border-white aspect-video flex items-center justify-center group">
            
            {{-- Video Asli (Hidden) --}}
            <video id="videoElement" autoplay playsinline class="hidden"></video>
            
            {{-- Hasil AR --}}
            <img id="processedImage" class="w-full h-full object-cover" src="https://via.placeholder.com/800x600?text=Silakan+Pilih+Pengaturan+di+Kanan" alt="AR Feed">
            
            {{-- Loading --}}
            <div id="loading" class="hidden absolute inset-0 flex items-center justify-center bg-black/50 z-10">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"></div>
            </div>

            {{-- Guide Text (Muncul saat kamera aktif) --}}
            <div id="guideText" class="absolute top-6 w-full text-center hidden">
                <span class="bg-black/60 backdrop-blur-sm text-white px-6 py-2 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg">
                    <i class="fas fa-expand-arrows-alt mr-2"></i> Posisikan Badan di Tengah Frame
                </span>
            </div>

            {{-- Logo Watermark --}}
            <div class="absolute bottom-4 right-4 opacity-50 pointer-events-none">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Lembaga_Penjamin_Simpanan_logo.svg/1200px-Lembaga_Penjamin_Simpanan_logo.svg.png" class="h-10 bg-white/80 p-1 rounded">
            </div>
        </div>
    </div>

    {{-- Sidebar Config (WIZARD) --}}
    <div class="lg:col-span-1">
        <div id="setupWizard" class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 h-full flex flex-col">
            
            {{-- STEP 1: Jumlah Orang --}}
            <div id="step1" class="animate-in fade-in slide-in-from-right duration-300">
                <div class="mb-4">
                    <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Langkah 1</span>
                    <h3 class="font-bold text-lg text-slate-800">Jumlah Orang</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="setPeople(1)" class="p-4 border-2 rounded-2xl hover:border-orange-500 hover:bg-orange-50 transition text-center group">
                        <i class="fas fa-user text-2xl text-slate-300 group-hover:text-orange-600 mb-2"></i>
                        <p class="text-xs font-bold text-slate-600">1 Orang</p>
                    </button>
                    <button onclick="setPeople(2)" class="p-4 border-2 rounded-2xl hover:border-orange-500 hover:bg-orange-50 transition text-center group">
                        <i class="fas fa-users text-2xl text-slate-300 group-hover:text-orange-600 mb-2"></i>
                        <p class="text-xs font-bold text-slate-600">2 Orang</p>
                    </button>
                </div>
            </div>

            {{-- STEP 2: Gender --}}
            <div id="step2" class="hidden animate-in fade-in slide-in-from-right duration-300">
                <div class="mb-4">
                    <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Langkah 2</span>
                    <h3 class="font-bold text-lg text-slate-800">Pilih Gender</h3>
                </div>
                <div id="genderOptions" class="space-y-2">
                    {{-- Diisi via JS --}}
                </div>
                <button onclick="backToStep(1)" class="mt-4 text-xs text-slate-400 hover:text-orange-600"><i class="fas fa-arrow-left mr-1"></i> Kembali</button>
            </div>

            {{-- STEP 3: Tema Baju --}}
            <div id="step3" class="hidden animate-in fade-in slide-in-from-right duration-300">
                <div class="mb-4">
                    <span class="text-xs font-bold text-orange-600 uppercase tracking-wider">Langkah 3</span>
                    <h3 class="font-bold text-lg text-slate-800">Pilih Tema</h3>
                </div>
                
                <div class="space-y-2 overflow-y-auto max-h-[300px] pr-1 mb-4">
                    @foreach($bajuAdat as $baju)
                    <div onclick="selectTheme('{{ $baju['id'] }}', this)" 
                         class="theme-item cursor-pointer p-3 border-2 border-transparent rounded-xl flex items-center space-x-3 hover:bg-slate-50 transition">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                            <i class="fas fa-shirt text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700">{{ $baju['name'] }}</p>
                            <p class="text-[10px] text-slate-400 leading-none">{{ $baju['description'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button onclick="startCameraNow()" class="w-full bg-orange-600 text-white py-3 rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 transition transform active:scale-95">
                    <i class="fas fa-camera mr-2"></i> Mulai
                </button>
                <button onclick="backToStep(2)" class="mt-4 text-xs text-slate-400 hover:text-orange-600 block mx-auto"><i class="fas fa-arrow-left mr-1"></i> Ubah Gender</button>
            </div>

            {{-- Controls saat kamera aktif --}}
            <div id="activeControls" class="hidden mt-auto">
                <div class="p-4 bg-green-50 rounded-xl border border-green-200 text-center mb-4">
                    <p class="text-xs font-bold text-green-800"><i class="fas fa-check-circle mr-1"></i> Sistem Aktif</p>
                    <p id="statusInfo" class="text-[10px] text-green-600 mt-1">Mode: -</p>
                </div>
                <button onclick="capturePhoto()" class="w-full bg-white border-2 border-slate-200 text-slate-700 py-3 rounded-xl font-bold hover:bg-slate-50 transition mb-2">
                    <i class="fas fa-camera mr-2"></i> Ambil Foto
                </button>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    // Konfigurasi Global
    let config = {
        people: 1,
        gender: 'pria',
        theme: 'jawa', // default
        themeName: ''
    };

    let video = document.getElementById('videoElement');
    let processedImage = document.getElementById('processedImage');
    let intervalId = null;
    let isStreaming = false;

    // --- NAVIGATION LOGIC ---
    function setPeople(n) {
        config.people = n;
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
        
        const gOptions = document.getElementById('genderOptions');
        gOptions.innerHTML = ''; // Reset

        if(n === 1) {
            // Pilihan 1 Orang
            gOptions.innerHTML = `
                <button onclick="setGender('pria')" class="w-full p-3 border-2 border-slate-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 font-bold text-slate-600 text-sm transition text-left flex items-center">
                    <i class="fas fa-male w-8 text-center text-lg"></i> Pria
                </button>
                <button onclick="setGender('wanita')" class="w-full p-3 border-2 border-slate-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 font-bold text-slate-600 text-sm transition text-left flex items-center">
                    <i class="fas fa-female w-8 text-center text-lg"></i> Wanita
                </button>
            `;
        } else {
            // Pilihan 2 Orang
            gOptions.innerHTML = `
                <button onclick="setGender('pria_pria')" class="w-full p-3 border-2 border-slate-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 font-bold text-slate-600 text-sm transition text-left flex items-center">
                    <div class="w-8 text-center"><i class="fas fa-male"></i><i class="fas fa-male"></i></div> Pria & Pria
                </button>
                <button onclick="setGender('wanita_wanita')" class="w-full p-3 border-2 border-slate-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 font-bold text-slate-600 text-sm transition text-left flex items-center">
                     <div class="w-8 text-center"><i class="fas fa-female"></i><i class="fas fa-female"></i></div> Wanita & Wanita
                </button>
                <button onclick="setGender('pria_wanita')" class="w-full p-3 border-2 border-slate-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 font-bold text-slate-600 text-sm transition text-left flex items-center">
                     <div class="w-8 text-center"><i class="fas fa-male"></i><i class="fas fa-female"></i></div> Pria & Wanita
                </button>
            `;
        }
    }

    function setGender(g) {
        config.gender = g;
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.remove('hidden');
    }

    function backToStep(step) {
        // Hide all
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step3').classList.add('hidden');
        
        // Show target
        document.getElementById('step'+step).classList.remove('hidden');
    }

    function selectTheme(id, element) {
        config.theme = id;
        // Visual Selection
        document.querySelectorAll('.theme-item').forEach(el => {
            el.classList.remove('border-orange-500', 'bg-orange-50');
            el.classList.add('border-transparent');
        });
        element.classList.remove('border-transparent');
        element.classList.add('border-orange-500', 'bg-orange-50');
    }

    function resetSetup() {
        if(isStreaming) stopCamera();
        config = { people: 1, gender: 'pria', theme: 'jawa' };
        backToStep(1);
        document.getElementById('activeControls').classList.add('hidden');
        processedImage.src = "https://via.placeholder.com/800x600?text=Silakan+Pilih+Pengaturan+di+Kanan";
    }

    function stopCamera() {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        clearInterval(intervalId);
        isStreaming = false;
        document.getElementById('guideText').classList.add('hidden');
    }

    // --- CAMERA & AI LOGIC ---
    async function startCameraNow() {
        // Sembunyikan wizard, tampilkan kontrol aktif
        document.getElementById('step3').classList.add('hidden');
        document.getElementById('activeControls').classList.remove('hidden');
        document.getElementById('btnCapture').classList.remove('hidden');
        document.getElementById('statusInfo').innerText = `Mode: ${config.people} Orang (${config.gender.replace('_', ' & ')})`;

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } });
            video.srcObject = stream;
            video.play();
            isStreaming = true;
            
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('guideText').classList.remove('hidden');
            
            // Start Loop
            intervalId = setInterval(sendFrameToAI, 150); 

        } catch (err) {
            Swal.fire('Error Kamera', 'Pastikan izin kamera diberikan.', 'error');
            resetSetup();
        }
    }

    async function sendFrameToAI() {
        if (!isStreaming) return;

        // Capture Frame ke Canvas
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        
        canvas.toBlob(blob => {
            const formData = new FormData();
            formData.append('image', blob);
            // Kirim Konfigurasi User ke Python
            formData.append('num_people', config.people);
            formData.append('gender_mode', config.gender);
            formData.append('baju', config.theme);

            fetch('http://localhost:5000/process_ar', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    processedImage.src = 'data:image/jpeg;base64,' + data.image;
                    document.getElementById('loading').classList.add('hidden');
                }
            })
            .catch(err => console.error("API Error:", err));
        }, 'image/jpeg', 0.7);
    }

    function capturePhoto() {
        clearInterval(intervalId); // Pause
        Swal.fire({
            title: 'Foto Siap!',
            text: 'Cetak foto ini atau ambil ulang?',
            imageUrl: processedImage.src,
            imageWidth: 400,
            imageAlt: 'Hasil Foto',
            showCancelButton: true,
            confirmButtonText: 'Cetak Sekarang',
            confirmButtonColor: '#ea580c',
            cancelButtonText: 'Ambil Ulang'
        }).then((result) => {
            if (result.isConfirmed) {
                printResult();
            } else {
                // Resume
                if(isStreaming) intervalId = setInterval(sendFrameToAI, 150);
            }
        });
    }

    function printResult() {
        let printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print Photobooth LPS</title>');
        printWindow.document.write('<style>body{text-align:center; font-family:sans-serif; padding:20px;} img{max-width:100%; height:auto; border: 5px solid #ea580c; border-radius: 10px;} .footer{margin-top:20px; font-size:12px; color:#666;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2 style="color:#ea580c">LPS Virtual Photobooth</h2>');
        printWindow.document.write('<img src="' + processedImage.src + '"/>');
        printWindow.document.write('<div class="footer">Dicetak pada: ' + new Date().toLocaleString() + '</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
@endpush
@endsection