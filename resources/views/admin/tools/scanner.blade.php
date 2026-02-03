@extends('layouts.admin')
@section('title', 'Object Recognition AR')

@section('content')
    <div class="mb-8 text-center md:text-left">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">AR Object Scanner</h2>
        <p class="text-sm text-slate-500">Arahkan kamera ke objek untuk memunculkan infografis otomatis.</p>
    </div>

    <div class="max-w-4xl mx-auto px-4">
        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl bg-slate-900 h-[650px] border-4 border-white ring-8 ring-slate-100">
            {{-- Video Stream --}}
            <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
            <canvas id="canvas" class="hidden"></canvas>

            {{-- Scanning Animation --}}
            <div id="scanner-line" class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-orange-500 to-transparent shadow-[0_0_20px_rgba(234,88,12,1)] z-10"></div>

            {{-- INFOGRAPHIC AR LABEL (Side-by-Side Layout) --}}
            <div id="ar-floating-label" class="hidden absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 w-[95%] max-w-xl pointer-events-none transition-all duration-500">
                <div class="bg-white/95 backdrop-blur-xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/50 animate-float pointer-events-auto flex flex-row">
                    
                    {{-- KIRI: Gambar Aset --}}
                    <div class="w-1/3 min-w-[140px] relative bg-slate-200">
                        <img id="ar-asset-image" src="" class="w-full h-full object-cover" alt="asset">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                    </div>
                    
                    {{-- KANAN: Informasi Teks --}}
                    <div class="w-2/3 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                                <span class="text-[9px] font-bold text-green-600 uppercase tracking-widest">Object Identified</span>
                            </div>
                            
                            <h3 id="ar-asset-title" class="font-black text-slate-800 text-xl uppercase tracking-tighter leading-tight mb-3"></h3>
                            
                            <div class="space-y-1">
                                <label class="text-[9px] font-bold text-orange-500 uppercase tracking-widest block">Deskripsi Lengkap</label>
                                <div id="ar-asset-desc" class="text-xs text-slate-600 leading-relaxed text-justify pr-2 overflow-y-auto max-h-[180px]">
                                    {{-- Deskripsi muncul di sini --}}
                                </div>
                            </div>
                        </div>

                        {{-- Metadata Bottom --}}
                        <div class="flex gap-4 mt-4 pt-4 border-t border-slate-100">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-slate-400 uppercase">System Status</span>
                                <span class="text-[10px] font-bold text-slate-700 italic underline decoration-orange-400">Verified by AI</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[8px] font-bold text-slate-400 uppercase">Scan Time</span>
                                <span id="ar-detect-time" class="text-[10px] font-bold text-slate-700">--:--</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Decorative Line --}}
                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-32 h-1.5 bg-orange-500 rounded-full"></div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <div id="status-badge" class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 border border-slate-200">
                <span id="status-dot" class="w-2 h-2 rounded-full bg-orange-500 animate-pulse mr-2"></span>
                <p id="status-text" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Scanning Environment...</p>
            </div>
        </div>
    </div>

    <style>
        #scanner-line { animation: scan 2.5s infinite ease-in-out; }
        @keyframes scan { 0% { top: 15%; opacity: 0; } 50% { opacity: 1; } 100% { top: 85%; opacity: 0; } }

        .animate-float { animation: float 4s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(0.5deg); }
        }

        .ar-entry { animation: slideIn 0.6s cubic-bezier(0.23, 1, 0.32, 1); }
        @keyframes slideIn {
            from { opacity: 0; transform: translate(-50%, -40%) scale(0.95); }
            to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }

        /* Custom scrollbar */
        #ar-asset-desc::-webkit-scrollbar { width: 3px; }
        #ar-asset-desc::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        #ar-asset-desc::-webkit-scrollbar-thumb:hover { background: #ea580c; }
    </style>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const floatingLabel = document.getElementById('ar-floating-label');
        const statusText = document.getElementById('status-text');
        const statusDot = document.getElementById('status-dot');

        let lastDetectedTime = 0;
        let currentObjectId = null;
        const HIDE_TIMEOUT = 2500;

        function speak(text) {
            if (window.speechSynthesis.speaking) return;
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            window.speechSynthesis.speak(utterance);
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                video.srcObject = stream;
                video.onloadedmetadata = () => setInterval(scanObject, 1000);
                checkInactivity();
            });

        function scanObject() {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const context = canvas.getContext('2d');
                canvas.width = 400;
                canvas.height = (video.videoHeight / video.videoWidth) * 400;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                canvas.toBlob(blob => {
                    const formData = new FormData();
                    formData.append('image', blob);

                    fetch('http://localhost:5001/recognize', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            lastDetectedTime = Date.now();
                            if (currentObjectId !== data.filename) {
                                currentObjectId = data.filename;
                                fetchDetail(data.filename);
                            }
                        }
                    }).catch(() => {});
                }, 'image/jpeg', 0.5);
            }
        }

        function fetchDetail(filename) {
            const formData = new FormData();
            formData.append('filename', filename);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('admin.asset.detail') }}', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showAR(res.data);
                }
            });
        }

        function showAR(asset) {
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

            document.getElementById('ar-asset-title').innerText = asset.title;
            document.getElementById('ar-asset-desc').innerText = asset.description;
            document.getElementById('ar-asset-image').src = asset.image_url || '/shared/assets/placeholder.png';
            document.getElementById('ar-detect-time').innerText = timeStr;

            if (floatingLabel.classList.contains('hidden')) {
                floatingLabel.classList.remove('hidden');
                floatingLabel.classList.add('ar-entry');
                speak(asset.title);
            }

            statusText.innerText = "Target Locked";
            statusDot.classList.replace('bg-orange-500', 'bg-green-500');
        }

        function checkInactivity() {
            setInterval(() => {
                if (Date.now() - lastDetectedTime > HIDE_TIMEOUT && !floatingLabel.classList.contains('hidden')) {
                    hideAR();
                }
            }, 500);
        }

        function hideAR() {
            floatingLabel.classList.add('hidden');
            floatingLabel.classList.remove('ar-entry');
            currentObjectId = null;
            window.speechSynthesis.cancel();
            statusText.innerText = "Scanning Environment...";
            statusDot.classList.replace('bg-green-500', 'bg-orange-500');
        }
    </script>
@endsection