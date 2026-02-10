<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Interactive Slider</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #000;
            overflow: hidden;
        }

        /* Hilangkan Scrollbar tapi tetap bisa scroll via JS */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="h-screen w-screen bg-black relative">

    {{-- CONTAINER UTAMA --}}
    <div id="slider-container" class="w-full h-full relative flex items-center justify-center">
    </div>

    {{-- TOMBOL NAVIGASI (Floating Bottom Right) --}}
    <div class="fixed bottom-10 right-8 z-50">
        <button id="action-btn"
            class="bg-white/10 backdrop-blur-md border border-white/20 text-white pl-6 pr-2 py-3 rounded-full flex items-center gap-4 hover:bg-white/20 transition-all active:scale-95 shadow-2xl group">
            <span id="btn-text" class="font-bold text-xl tracking-wide uppercase">MULAI</span>
            <div
                class="w-12 h-12 bg-white text-black rounded-full flex items-center justify-center group-hover:rotate-90 transition-transform duration-300">
                <i id="btn-icon" class="fas fa-arrow-right text-xl"></i>
            </div>
        </button>
    </div>

    {{-- INDIKATOR PROGRESS (Atas) --}}
    <div class="fixed top-8 left-8 right-8 z-50 flex gap-2" id="progress-bar"></div>

    <script>
        // Data dari Controller
        const sliderData = @json($sliders);
        // Route Minigame
        const minigameUrl = "{{ route('minigame') }}";

        let currentIndex = 0;
        const container = document.getElementById('slider-container');
        const progressBar = document.getElementById('progress-bar');

        // --- INIT ---
        if (sliderData.length > 0) {
            renderProgress();
            loadSlide(0);
        } else {
            container.innerHTML = '<p class="text-white">Tidak ada gambar slider.</p>';
            document.getElementById('action-btn').style.display = 'none';
        }

        function renderProgress() {
            progressBar.innerHTML = '';
            sliderData.forEach((_, idx) => {
                let bar = document.createElement('div');
                bar.className =
                    `h-1.5 flex-1 rounded-full transition-all duration-300 ${idx === 0 ? 'bg-white' : 'bg-white/20'}`;
                bar.id = `bar-${idx}`;
                progressBar.appendChild(bar);
            });
        }

        function updateProgress(index) {
            sliderData.forEach((_, idx) => {
                const bar = document.getElementById(`bar-${idx}`);
                if (idx <= index) {
                    bar.className = "h-1.5 flex-1 rounded-full transition-all duration-300 bg-white";
                } else {
                    bar.className = "h-1.5 flex-1 rounded-full transition-all duration-300 bg-white/20";
                }
            });
        }

        // --- CORE LOGIC (DIPERBAIKI) ---
        function loadSlide(index) {
            const item = sliderData[index];
            container.innerHTML = ''; // Reset container

            // [FIX] Ambil tombol yang AKTIF saat ini dari DOM (bukan variabel global lama)
            const currentBtn = document.getElementById('action-btn');

            // Clone tombol untuk menghapus semua event listener lama (onclick)
            const newBtn = currentBtn.cloneNode(true);
            currentBtn.parentNode.replaceChild(newBtn, currentBtn);

            // Gunakan referensi tombol baru
            const btn = newBtn;

            // Ambil ulang elemen teks & icon dari tombol baru
            const uiText = document.getElementById('btn-text');
            const uiIcon = document.getElementById('btn-icon');

            // --- Logic Tampilan ---
            if (item.type === 'portrait') {
                // === MODE PORTRAIT ===
                container.innerHTML =
                    `<img src="/${item.image_path}" class="w-full h-full object-cover animate-fade-in" onerror="this.style.display='none'; alert('Gagal memuat gambar')">`;

                // Cek apakah ini slide terakhir
                if (index === sliderData.length - 1) {
                    setupMinigameButton(btn, uiText, uiIcon);
                } else {
                    uiText.innerText = "LANJUT";
                    uiIcon.className = "fas fa-arrow-right text-xl";
                    // Reset style tombol jika sebelumnya minigame/geser
                    resetButtonStyle(btn);

                    btn.onclick = () => {
                        currentIndex++;
                        loadSlide(currentIndex);
                    };
                }

            } else {
                // === MODE LANDSCAPE ===
                const wrapper = document.createElement('div');
                wrapper.className = "w-full h-full overflow-x-auto hide-scrollbar relative scroll-smooth";
                wrapper.id = "landscape-wrapper";
                wrapper.innerHTML = `<img src="/${item.image_path}" class="h-full w-auto max-w-none">`;
                container.appendChild(wrapper);

                const checkScroll = () => {
                    if (!wrapper) return;
                    // Toleransi 5px
                    const isAtEnd = (wrapper.scrollLeft + wrapper.clientWidth) >= (wrapper.scrollWidth - 5);

                    if (isAtEnd) {
                        if (index === sliderData.length - 1) {
                            setupMinigameButton(btn, uiText, uiIcon);
                        } else {
                            uiText.innerText = "LANJUT";
                            uiIcon.className = "fas fa-arrow-right text-xl";
                            resetButtonStyle(btn);
                            btn.onclick = () => {
                                currentIndex++;
                                loadSlide(currentIndex);
                            };
                        }
                    } else {
                        uiText.innerText = "GESER";
                        uiIcon.className = "fas fa-chevron-right text-xl";
                        resetButtonStyle(btn);
                        btn.onclick = () => {
                            wrapper.scrollBy({
                                left: window.innerWidth * 0.8,
                                behavior: 'smooth'
                            });
                            setTimeout(checkScroll, 600);
                        };
                    }
                };

                // Tunggu gambar load sebentar lalu cek scroll
                setTimeout(checkScroll, 100);

                wrapper.addEventListener('scroll', () => {
                    if ((wrapper.scrollLeft + wrapper.clientWidth) >= (wrapper.scrollWidth - 10)) {
                        checkScroll();
                    }
                });
            }

            updateProgress(index);
        }

        function resetButtonStyle(btn) {
            // Kembalikan style tombol ke default (transparan/kaca)
            btn.className =
                "bg-white/10 backdrop-blur-md border border-white/20 text-white pl-6 pr-2 py-3 rounded-full flex items-center gap-4 hover:bg-white/20 transition-all active:scale-95 shadow-2xl group";
        }

        function setupMinigameButton(btn, text, icon) {
            text.innerText = "MINIGAME";
            icon.className = "fas fa-gamepad text-xl";
            // Style khusus minigame (Orange)
            btn.className =
                "bg-orange-600 border border-orange-400 text-white pl-6 pr-2 py-3 rounded-full flex items-center gap-4 hover:bg-orange-700 transition-all shadow-2xl scale-110 animate-pulse";
            btn.onclick = () => {
                window.location.href = minigameUrl;
            };
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</body>

</html>
