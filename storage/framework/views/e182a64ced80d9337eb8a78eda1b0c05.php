<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photobooth AI - LPS</title>

    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .active-gender { background-color: #ea580c; color: white; border-color: #ea580c; }
        [x-cloak] { display: none !important; }
        
        /* ─── CSS KHUSUS PRINT (FIXED) ─── */
        @media print {
            /* Sembunyikan semua elemen bawaan halaman */
            body > *:not(#print-area) {
                display: none !important;
            }

            /* Reset Body & HTML agar full page */
            body, html {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                background-color: white;
                overflow: hidden; /* Hilangkan scrollbar */
            }

            /* Tampilkan Area Print */
            #print-area {
                display: flex !important; /* Paksa tampil menimpa class 'hidden' */
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: white;
                align-items: center;
                justify-content: center;
            }

            /* Atur gambar agar pas di tengah kertas */
            #print-image-src {
                max-width: 90%; /* Beri margin agar tidak terpotong printer */
                max-height: 90%;
                width: auto;
                height: auto;
                object-fit: contain;
                box-shadow: none;
                border: 1px solid #ddd; /* Opsional: border tipis */
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col relative bg-slate-50">

    
    <main class="flex-grow flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-7xl mx-auto">
            
            
            <div class="mb-6 text-center lg:text-left">
                <h2 class="text-2xl font-bold text-slate-800">Photobooth AI</h2>
                <p class="text-slate-500 text-sm">Virtual Try-On (Single Mode)</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                
                <div class="lg:col-span-1 space-y-4">
                    
                    
                    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Mode Pakaian</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="setGenderMode('pria')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition active-gender" data-mode="pria">
                                <i class="fas fa-male mr-1"></i> Pria
                            </button>
                            <button onclick="setGenderMode('wanita')" class="gender-btn py-2 px-3 rounded-xl text-xs font-bold border hover:bg-orange-50 transition" data-mode="wanita">
                                <i class="fas fa-female mr-1"></i> Wanita
                            </button>
                        </div>
                    </div>

                    
                    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex-grow">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Koleksi Baju</label>
                        <div class="grid grid-cols-2 gap-2 max-h-[400px] overflow-y-auto pr-1 custom-scrollbar">
                            <?php $__currentLoopData = $bajuAdat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $baju): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button onclick="selectBaju('<?php echo e($baju['id']); ?>')" 
                                    class="baju-btn group relative rounded-xl overflow-hidden border-2 border-slate-100 hover:border-orange-500 transition-all text-left"
                                    data-id="<?php echo e($baju['id']); ?>">
                                <img src="<?php echo e(asset('assets/baju/' . $baju['id'] . '_pria.png')); ?>" 
                                     onerror="this.src='<?php echo e(asset('assets/baju/' . $baju['id'] . '.png')); ?>'"
                                     class="w-full h-20 object-cover opacity-80 group-hover:opacity-100 transition">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-1">
                                    <span class="text-[9px] text-white font-bold block truncate"><?php echo e($baju['name']); ?></span>
                                </div>
                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-3">
                    <div class="relative bg-black rounded-3xl overflow-hidden shadow-2xl aspect-video group">
                        
                        <div id="loading-cam" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-black/90 text-white">
                            <i class="fas fa-circle-notch fa-spin text-4xl text-orange-500 mb-4"></i>
                            <p class="animate-pulse" id="loading-text">Menyiapkan AI...</p>
                        </div>

                        <video id="video-input" class="hidden" autoplay playsinline></video>
                        <canvas id="output-canvas" class="w-full h-full object-cover"></canvas>

                        
                        <div class="absolute bottom-6 left-0 right-0 flex justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button onclick="takeSnapshot()" class="bg-white/20 backdrop-blur-md border-4 border-white h-16 w-16 rounded-full flex items-center justify-center hover:bg-orange-600 hover:border-orange-600 transition-all shadow-lg transform hover:scale-110">
                                <i class="fas fa-camera text-2xl text-white"></i>
                            </button>
                        </div>
                    </div>
                    
                    <p class="text-center text-slate-400 text-xs mt-3">
                        <i class="fas fa-bolt text-yellow-400 mr-1"></i> Powered by MediaPipe Vision
                    </p>
                </div>
            </div>
        </div>
    </main>

    
    <div id="result-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all scale-95 opacity-0" id="modal-content">
            
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-800">Hasil Foto</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <div class="p-6 bg-slate-50 text-center">
                <img id="result-image" src="" class="rounded-xl shadow-lg w-full border-4 border-white mb-6">
                
                <div class="flex gap-3 justify-center">
                    <button onclick="printPolaroid()" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-slate-900 shadow-lg flex items-center gap-2">
                        <i class="fas fa-print"></i> Cetak Foto
                    </button>
                    <button onclick="closeModal()" class="bg-white text-slate-700 border border-slate-300 px-6 py-3 rounded-xl font-bold text-sm hover:bg-slate-50 flex items-center gap-2">
                        <i class="fas fa-redo"></i> Foto Ulang
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div id="print-area" class="hidden">
        <img id="print-image-src" alt="Hasil Foto">
    </div>

    
    <script type="module">
        import { PoseLandmarker, FilesetResolver } from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";

        const video = document.getElementById("video-input");
        const canvas = document.getElementById("output-canvas");
        const ctx = canvas.getContext("2d");
        const loadingEl = document.getElementById("loading-cam");
        let poseLandmarker = undefined;
        let runningMode = "VIDEO";
        let lastVideoTime = -1;

        let state = {
            bajuId: "<?php echo e($bajuAdat[0]['id'] ?? 'jawa'); ?>",
            genderMode: 'pria', 
            assets: {} 
        };

        // --- 1. INISIALISASI MEDIAPIPE (Single Person Mode) ---
        const createPoseLandmarker = async () => {
            const vision = await FilesetResolver.forVisionTasks("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm");
            poseLandmarker = await PoseLandmarker.createFromOptions(vision, {
                baseOptions: {
                    modelAssetPath: `https://storage.googleapis.com/mediapipe-models/pose_landmarker/pose_landmarker_lite/float16/1/pose_landmarker_lite.task`,
                    delegate: "GPU"
                },
                runningMode: runningMode,
                numPoses: 1
            });
            loadingEl.classList.add('hidden');
            startCamera();
            loadAssets();
        };
        createPoseLandmarker();

        function startCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { width: 1280, height: 720 } }).then((stream) => {
                    video.srcObject = stream;
                    video.addEventListener("loadeddata", predictWebcam);
                });
            }
        }

        // --- 2. LOOP PREDIKSI ---
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
                    
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    
                    // Gambar Video Mirror
                    ctx.save();
                    ctx.translate(canvas.width, 0);
                    ctx.scale(-1, 1);
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    ctx.restore();

                    // Gambar Baju
                    if (results.landmarks.length > 0) {
                        renderSingleBaju(results.landmarks[0], state.genderMode);
                    }
                }
            }
            window.requestAnimationFrame(predictWebcam);
        }

        function renderSingleBaju(landmarks, genderType) {
            const leftShoulder = landmarks[11];
            const rightShoulder = landmarks[12];

            if (!leftShoulder || !rightShoulder || leftShoulder.visibility < 0.5) return;

            const w = canvas.width;
            const h = canvas.height;
            
            // Konversi Koordinat Layar Mirror
            const lsX = (1 - leftShoulder.x) * w; 
            const lsY = leftShoulder.y * h;
            const rsX = (1 - rightShoulder.x) * w;
            const rsY = rightShoulder.y * h;

            const centerX = (lsX + rsX) / 2;
            const centerY = (lsY + rsY) / 2;
            const shoulderWidth = Math.hypot(rsX - lsX, rsY - lsY);
            const dx = rsX - lsX; 
            const dy = rsY - lsY;
            const angle = Math.atan2(dy, dx); 

            const imgKey = `${state.bajuId}_${genderType}`;
            const img = state.assets[imgKey] || state.assets[`${state.bajuId}_pria`];

            if (img) {
                const scale = 2.2; 
                const imgW = shoulderWidth * scale;
                const aspectRatio = img.height / img.width;
                const imgH = imgW * aspectRatio;
                const yOffset = imgH * 0.12; 

                ctx.save();
                ctx.translate(centerX, centerY);
                ctx.rotate(angle); 
                ctx.scale(-1, 1); 
                ctx.drawImage(img, -imgW/2, -yOffset, imgW, imgH);
                ctx.restore();
            }
        }

        // --- 3. HELPER FUNCTIONS ---
        function loadAssets() {
            const genders = ['pria', 'wanita'];
            const id = state.bajuId;
            const basePath = "<?php echo e(asset('assets/baju')); ?>";
            genders.forEach(g => {
                const key = `${id}_${g}`;
                if (!state.assets[key]) {
                    const img = new Image();
                    img.src = `${basePath}/${id}_${g}.png`;
                    img.onload = () => { state.assets[key] = img; };
                }
            });
        }

        window.setGenderMode = (mode) => {
            state.genderMode = mode;
            document.querySelectorAll('.gender-btn').forEach(btn => {
                btn.classList.toggle('active-gender', btn.dataset.mode === mode);
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

        // --- 4. LOGIKA SNAPSHOT & MODAL ---
        window.takeSnapshot = () => {
            const dataURL = canvas.toDataURL('image/png');
            document.getElementById('result-image').src = dataURL;
            
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

        // --- 5. LOGIKA PRINT POLAROID (FIXED) ---
        window.printPolaroid = () => {
            const resultImg = document.getElementById('result-image');
            
            // 1. Setup Canvas untuk Print
            const printCanvas = document.createElement('canvas');
            const ctxPrint = printCanvas.getContext('2d');
            
            const w = resultImg.naturalWidth;
            const h = resultImg.naturalHeight;
            const paddingBottom = 200; // Ruang untuk Polaroid Footer
            
            printCanvas.width = w;
            printCanvas.height = h + paddingBottom;

            // 2. Background Putih Polaroid
            ctxPrint.fillStyle = "#ffffff";
            ctxPrint.fillRect(0, 0, printCanvas.width, printCanvas.height);

            // 3. Gambar Foto (Centered Horizontally if needed, but here simple draw)
            ctxPrint.drawImage(resultImg, 0, 0);

            // 4. Load Logo LPS
            const logo = new Image();
            // Ganti path logo di sini jika perlu
            logo.src = "<?php echo e(asset('assets/logo-lps.png')); ?>"; 
            
            logo.onload = () => {
                // Gambar Logo di Kiri Bawah
                const logoW = 100;
                const logoH = (logo.height / logo.width) * logoW;
                ctxPrint.drawImage(logo, 30, h + 30, logoW, logoH);
                
                finishPrint(printCanvas, h);
            };
            
            // Jika logo gagal/lama, tetap print teks
            logo.onerror = () => { finishPrint(printCanvas, h); };
        };

        function finishPrint(canvas, imgHeight) {
            const ctxPrint = canvas.getContext('2d');
            const w = canvas.width;

            // Kode Unik Acak
            const randomCode = 'LPS-' + Math.floor(1000 + Math.random() * 9000);

            // Setting Font
            ctxPrint.textAlign = "right";
            ctxPrint.textBaseline = "middle";
            ctxPrint.fillStyle = "#1e293b"; // Slate-800

            // Tulis Kode (Besar)
            ctxPrint.font = "bold 40px 'Courier New', monospace";
            ctxPrint.fillText(`Code: ${randomCode}`, w - 30, imgHeight + 70);

            // Tulis Keterangan (Kecil)
            ctxPrint.font = "italic 20px 'Arial', sans-serif";
            ctxPrint.fillStyle = "#64748b"; // Slate-500
            ctxPrint.fillText("Tukarkan kode ini untuk mendapatkan kopi gratis!", w - 30, imgHeight + 110);

            // Masukkan ke Image Tag Print
            const finalDataURL = canvas.toDataURL('image/png');
            const printImg = document.getElementById('print-image-src');
            printImg.src = finalDataURL;

            // Beri jeda agar gambar ter-render di DOM sebelum window.print()
            setTimeout(() => {
                window.print();
            }, 500);
        }

        // Init Default
        selectBaju("<?php echo e($bajuAdat[0]['id'] ?? 'jawa'); ?>");
    </script>
</body>
</html><?php /**PATH /var/www/html/resources/views/admin/tools/photobooth.blade.php ENDPATH**/ ?>