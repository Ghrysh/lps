@extends('layouts.admin')
@section('title', 'Photobooth AI (Fixed Vertical)')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Photobooth AI</h2>
            <p class="text-slate-500 text-sm">Virtual Try-On (Support 1 & 2 Orang)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        {{-- PANEL KONTROL (KIRI) --}}
        <div class="lg:col-span-1 space-y-4">
            
            {{-- 1. Pilihan Jumlah Orang --}}
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Jumlah Orang</label>
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button onclick="setPeopleMode(1)" id="btn-people-1" class="flex-1 py-2 rounded-lg text-sm font-bold bg-white text-orange-600 shadow-sm transition">
                        <i class="fas fa-user mr-1"></i> 1 Orang
                    </button>
                    <button onclick="setPeopleMode(2)" id="btn-people-2" class="flex-1 py-2 rounded-lg text-sm font-bold text-slate-500 hover:text-slate-700 transition">
                        <i class="fas fa-user-group mr-1"></i> 2 Orang
                    </button>
                </div>
            </div>

            {{-- 2. Pilihan Mode Gender --}}
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Mode Pakaian</label>
                
                {{-- Opsi 1 Orang --}}
                <div id="gender-options-1" class="grid grid-cols-2 gap-2">
                    <button onclick="setGenderMode('pria')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition active-gender" data-mode="pria">
                        Pria
                    </button>
                    <button onclick="setGenderMode('wanita')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition" data-mode="wanita">
                        Wanita
                    </button>
                </div>

                {{-- Opsi 2 Orang (Couple) --}}
                <div id="gender-options-2" class="hidden grid grid-cols-1 gap-2">
                    <button onclick="setGenderMode('pria_wanita')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition" data-mode="pria_wanita">
                        Couple (Pria & Wanita)
                    </button>
                    <button onclick="setGenderMode('pria_pria')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition" data-mode="pria_pria">
                        Bestie (Pria & Pria)
                    </button>
                    <button onclick="setGenderMode('wanita_wanita')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition" data-mode="wanita_wanita">
                        Bestie (Wanita & Wanita)
                    </button>
                </div>
            </div>

            {{-- 3. Pilihan Baju --}}
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex-grow">
                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Koleksi Baju</label>
                <div class="grid grid-cols-2 gap-2 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                    @foreach($bajuAdat as $baju)
                    <button onclick="selectBaju('{{ $baju['id'] }}')" 
                            class="baju-btn group relative rounded-xl overflow-hidden border-2 border-slate-100 hover:border-orange-500 transition-all text-left"
                            data-id="{{ $baju['id'] }}">
                        <img src="{{ asset('assets/baju/' . $baju['id'] . '_pria.png') }}" 
                             onerror="this.src='{{ asset('assets/baju/' . $baju['id'] . '.png') }}'"
                             class="w-full h-20 object-cover opacity-80 group-hover:opacity-100 transition">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-1">
                            <span class="text-[9px] text-white font-bold block truncate">{{ $baju['name'] }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- AREA KAMERA (KANAN) --}}
        <div class="lg:col-span-3">
            <div class="relative bg-black rounded-3xl overflow-hidden shadow-2xl aspect-video group">
                
                {{-- Loading Spinner --}}
                <div id="loading-cam" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/90 text-white">
                    <i class="fas fa-circle-notch fa-spin text-4xl text-orange-500 mb-4"></i>
                    <p class="animate-pulse" id="loading-text">Menyiapkan AI...</p>
                </div>

                {{-- Video Input --}}
                <video id="video-input" class="hidden" autoplay playsinline></video>
                
                {{-- Output Canvas --}}
                <canvas id="output-canvas" class="w-full h-full object-cover"></canvas>

                {{-- Tombol Capture --}}
                <div class="absolute bottom-6 left-0 right-0 flex justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button onclick="takeSnapshot()" class="bg-white/20 backdrop-blur-md border-4 border-white h-16 w-16 rounded-full flex items-center justify-center hover:bg-orange-600 hover:border-orange-600 transition-all shadow-lg transform hover:scale-110">
                        <i class="fas fa-camera text-2xl text-white"></i>
                    </button>
                </div>
            </div>
            
            <p class="text-center text-slate-400 text-xs mt-3">
                <i class="fas fa-bolt text-yellow-400 mr-1"></i> Powered by MediaPipe Vision (Support 2 Orang)
            </p>
        </div>
    </div>
</div>

{{-- MODAL HASIL FOTO --}}
<div id="result-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all scale-95 opacity-0" id="modal-content">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-slate-800">Hasil Foto</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="p-6 bg-slate-50 text-center">
            <img id="result-image" src="" class="rounded-xl shadow-lg w-full border-4 border-white mb-4">
            <div class="flex gap-3 justify-center">
                <a id="download-link" href="#" download="lps-photobooth.png" class="bg-orange-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-orange-700 shadow-lg">
                    <i class="fas fa-download mr-2"></i> Simpan
                </a>
                <button onclick="closeModal()" class="bg-white text-slate-700 border border-slate-300 px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-50">
                    Foto Lagi
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .active-gender { background-color: #ea580c; color: white; border-color: #ea580c; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
@endpush

@push('scripts')
<script type="module">
    import {
        PoseLandmarker,
        FilesetResolver
    } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";

    const video = document.getElementById("video-input");
    const canvas = document.getElementById("output-canvas");
    const ctx = canvas.getContext("2d");
    const loadingEl = document.getElementById("loading-cam");
    let poseLandmarker = undefined;
    let runningMode = "VIDEO";
    let lastVideoTime = -1;

    let state = {
        bajuId: "{{ $bajuAdat[0]['id'] ?? 'jawa' }}",
        peopleCount: 1,
        genderMode: 'pria', 
        assets: {} 
    };

    const createPoseLandmarker = async () => {
        const vision = await FilesetResolver.forVisionTasks(
            "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm"
        );
        poseLandmarker = await PoseLandmarker.createFromOptions(vision, {
            baseOptions: {
                modelAssetPath: `https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/1/pose_landmarker_lite.task`,
                delegate: "GPU"
            },
            runningMode: runningMode,
            numPoses: 2
        });
        
        loadingEl.classList.add('hidden');
        startCamera();
        loadAssets();
    };
    createPoseLandmarker();

    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: { width: 1280, height: 720 } })
                .then((stream) => {
                    video.srcObject = stream;
                    video.addEventListener("loadeddata", predictWebcam);
                });
        }
    }

    async function predictWebcam() {
        if(canvas.width !== video.videoWidth) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
        }

        let startTimeMs = performance.now();
        if (lastVideoTime !== video.currentTime) {
            lastVideoTime = video.currentTime;
            
            if (poseLandmarker) {
                const results = poseLandmarker.detectForVideo(video, startTimeMs);
                
                // Clear Canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // 1. GAMBAR VIDEO MIRROR
                ctx.save();
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                ctx.restore();

                // 2. GAMBAR BAJU
                if (results.landmarks.length > 0) {
                    drawClothesLogic(results.landmarks);
                }
            }
        }
        window.requestAnimationFrame(predictWebcam);
    }

    function drawClothesLogic(landmarksArray) {
        // Logic 2 Orang
        let people = landmarksArray.map(landmarks => {
            let noseX = (1.0 - landmarks[0].x); // Koordinat Layar (Mirror)
            return { landmarks: landmarks, screenX: noseX };
        });

        // Urutkan Kiri ke Kanan Layar
        people.sort((a, b) => a.screenX - b.screenX);

        let clothesToWear = [];
        if (state.peopleCount === 1) {
            clothesToWear = [state.genderMode];
        } else {
            if (state.genderMode === 'pria_wanita') clothesToWear = ['pria', 'wanita'];
            else if (state.genderMode === 'pria_pria') clothesToWear = ['pria', 'pria'];
            else if (state.genderMode === 'wanita_wanita') clothesToWear = ['wanita', 'wanita'];
            else clothesToWear = ['pria', 'pria'];
        }

        people.forEach((person, index) => {
            if (index < clothesToWear.length) {
                renderSingleBaju(person.landmarks, clothesToWear[index]);
            }
        });
    }

    function renderSingleBaju(landmarks, genderType) {
        // Ambil Bahu MediaPipe (11=KiriUser, 12=KananUser)
        const leftShoulder = landmarks[11];
        const rightShoulder = landmarks[12];

        if (!leftShoulder || !rightShoulder || leftShoulder.visibility < 0.5) return;

        const w = canvas.width;
        const h = canvas.height;
        
        // 1. KONVERSI KOORDINAT LAYAR (MIRROR)
        // User Left Shoulder ada di Kiri Layar (karena cermin, kiri=kiri)
        const lsX = (1 - leftShoulder.x) * w; 
        const lsY = leftShoulder.y * h;
        const rsX = (1 - rightShoulder.x) * w;
        const rsY = rightShoulder.y * h;

        // 2. HITUNG PUSAT & UKURAN
        const centerX = (lsX + rsX) / 2;
        const centerY = (lsY + rsY) / 2;
        const shoulderWidth = Math.hypot(rsX - lsX, rsY - lsY);

        // 3. HITUNG SUDUT ROTASI (YANG BENAR)
        // Kita hitung vektor dari Bahu Kiri(Screen Left) ke Bahu Kanan(Screen Right)
        // Vektor ini mengarah ke Kanan (Sudut ~0 derajat jika lurus)
        // lsX = Screen Left (kecil), rsX = Screen Right (besar)
        // Note: Di MediaPipe Mirroring:
        // leftShoulder (11) ada di SISI KIRI LAYAR (lsX ~ 200)
        // rightShoulder (12) ada di SISI KANAN LAYAR (rsX ~ 800)
        // Jadi rsX - lsX = Positif.
        const dx = rsX - lsX; 
        const dy = rsY - lsY;
        const angle = Math.atan2(dy, dx); 

        // Load Asset
        const imgKey = `${state.bajuId}_${genderType}`;
        const img = state.assets[imgKey] || state.assets[`${state.bajuId}_pria`];

        if (img) {
            const scale = 2.2; 
            const imgW = shoulderWidth * scale;
            const aspectRatio = img.height / img.width;
            const imgH = imgW * aspectRatio;

            // 4. OFFSET VERTIKAL (PENTING!)
            // Agar baju tidak naik ke kepala. Kita turunkan sedikit dari titik tengah bahu.
            // Bahu baju biasanya di 15-20% dari atas gambar.
            // Kita pasang titik (0,0) baju di posisi bahu layar.
            const yOffset = imgH * 0.12; 

            ctx.save();
            ctx.translate(centerX, centerY);
            
            // 5. ROTASI & FLIP (SOLUSI FINAL)
            ctx.rotate(angle); 
            
            // FLIP HORIZONTAL SAJA (-1, 1)
            // Ini membuat sisi Kanan Baju (Lengan Kanan) pindah ke Kiri (sesuai cermin)
            // TAPI TIDAK MEMBALIK ATAS-BAWAH (Vertikal tetap 1)
            ctx.scale(-1, 1); 

            // Gambar (Offset Y Negatif = Naik, Positif = Turun? DrawImage: y)
            // Kita mau titik bahu baju (sekitar y=20) ada di 0.
            // Jadi kita gambar mulai dari y = -20.
            ctx.drawImage(img, -imgW/2, -yOffset, imgW, imgH);
            
            ctx.restore();
        }
    }

    // --- ASSET MANAGER & UI (Sama) ---
    function loadAssets() {
        const genders = ['pria', 'wanita'];
        const id = state.bajuId;
        const basePath = "{{ asset('assets/baju') }}";
        genders.forEach(g => {
            const key = `${id}_${g}`;
            if (!state.assets[key]) {
                const img = new Image();
                img.src = `${basePath}/${id}_${g}.png`;
                img.onload = () => { state.assets[key] = img; };
            }
        });
    }

    window.setPeopleMode = (count) => {
        state.peopleCount = count;
        poseLandmarker.setOptions({ numPoses: count }); 
        
        document.getElementById('btn-people-1').className = count === 1 ? "flex-1 py-2 rounded-lg text-sm font-bold bg-white text-orange-600 shadow-sm transition" : "flex-1 py-2 rounded-lg text-sm font-bold text-slate-500 hover:text-slate-700 transition";
        document.getElementById('btn-people-2').className = count === 2 ? "flex-1 py-2 rounded-lg text-sm font-bold bg-white text-orange-600 shadow-sm transition" : "flex-1 py-2 rounded-lg text-sm font-bold text-slate-500 hover:text-slate-700 transition";

        document.getElementById('gender-options-1').classList.toggle('hidden', count !== 1);
        document.getElementById('gender-options-2').classList.toggle('hidden', count !== 2);
        
        setGenderMode(count === 1 ? 'pria' : 'pria_wanita');
    };

    window.setGenderMode = (mode) => {
        state.genderMode = mode;
        document.querySelectorAll('.gender-btn').forEach(btn => {
            if(btn.dataset.mode === mode) btn.classList.add('active-gender');
            else btn.classList.remove('active-gender');
        });
        loadAssets(); 
    };

    window.selectBaju = (id) => {
        state.bajuId = id;
        document.querySelectorAll('.baju-btn').forEach(btn => {
            if(btn.dataset.id === id) btn.classList.add('border-orange-500', 'ring-2', 'ring-orange-200');
            else btn.classList.remove('border-orange-500', 'ring-2', 'ring-orange-200');
        });
        loadAssets();
    };

    window.takeSnapshot = () => {
        const dataURL = canvas.toDataURL('image/png');
        document.getElementById('result-image').src = dataURL;
        document.getElementById('download-link').href = dataURL;
        const modal = document.getElementById('result-modal');
        const content = document.getElementById('modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    window.closeModal = () => {
        const modal = document.getElementById('result-modal');
        const content = document.getElementById('modal-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    };

    selectBaju("{{ $bajuAdat[0]['id'] ?? 'jawa' }}");
</script>
@endpush