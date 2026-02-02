
<?php $__env->startSection('title', 'LPS Mini Game'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-slate-800">LPS Smart Quiz</h2>
            <p class="text-slate-500">Jawab cepat untuk skor lebih tinggi!</p>
        </div>

        
        <div class="bg-white rounded-3xl shadow-2xl border-4 border-white overflow-hidden relative min-h-[500px]">

            
            <div id="start-screen"
                class="absolute inset-0 flex flex-col items-center justify-center bg-orange-50 z-30 p-8 text-center">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mb-6 animate-bounce">
                    <i class="fas fa-bolt text-4xl text-orange-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">Siap Bermain?</h3>
                <p class="text-slate-500 mb-2">Kamu punya waktu terbatas per soal.</p>
                <p class="text-xs text-orange-600 font-bold uppercase tracking-widest mb-6">Total <?php echo e(count($questions)); ?>

                    Soal</p>

                <button onclick="startGame()"
                    class="bg-orange-600 text-white px-10 py-4 rounded-full font-bold text-xl hover:bg-orange-700 transition shadow-lg transform hover:scale-105">
                    MULAI SEKARANG
                </button>
            </div>

            
            <div id="result-screen"
                class="hidden absolute inset-0 flex flex-col items-center justify-center bg-white z-30 p-8 text-center">
                <div id="score-icon" class="mb-2 text-7xl animate-bounce">🏆</div>
                <h3 class="text-3xl font-bold text-slate-800 mb-1">Permainan Selesai!</h3>

                
                <p class="text-sm text-slate-500 uppercase tracking-widest font-bold mb-2">Total Skor Akhir</p>
                <div class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-yellow-500 mb-6"
                    id="final-score">0</div>

                
                <div class="grid grid-cols-2 gap-4 w-full max-w-xs mb-8">
                    <div class="bg-green-50 p-4 rounded-2xl border-2 border-green-100 flex flex-col items-center">
                        <span class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Benar</span>
                        <div class="text-3xl font-black text-green-700 flex items-center gap-2">
                            <i class="fas fa-check-circle text-lg"></i> <span id="count-correct">0</span>
                        </div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-2xl border-2 border-red-100 flex flex-col items-center">
                        <span class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Salah</span>
                        <div class="text-3xl font-black text-red-700 flex items-center gap-2">
                            <i class="fas fa-times-circle text-lg"></i> <span id="count-wrong">0</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    
                    <button onclick="location.reload()"
                        class="bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition border border-slate-200">
                        <i class="fas fa-redo mr-2"></i> Main Lagi
                    </button>
                    <button onclick="location.reload()"
                        class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition shadow-lg">
                        <i class="fas fa-home mr-2"></i> Halaman Awal
                    </button>
                </div>
            </div>

            
            <div id="game-area" class="hidden h-full flex flex-col relative">

                
                <div class="bg-slate-900 text-white p-4 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="bg-white/20 px-3 py-1 rounded-lg text-xs font-bold">SOAL <span
                                id="q-number">1</span>/<?php echo e(count($questions)); ?></span>
                    </div>
                    <div class="font-bold text-xl text-yellow-400">
                        <i class="fas fa-star mr-1 text-sm"></i> <span id="current-score">0</span>
                    </div>
                </div>

                
                <div class="w-full bg-slate-200 h-2">
                    <div id="timer-bar"
                        class="bg-gradient-to-r from-green-500 to-green-400 h-2 transition-all duration-100 ease-linear"
                        style="width: 100%"></div>
                </div>

                
                <div class="p-8 flex-grow flex flex-col justify-center">

                    
                    <div class="text-center mb-4">
                        <span id="timer-text" class="text-3xl font-black text-slate-800">00</span>
                        <span class="text-xs font-bold text-slate-400 uppercase block">Detik Tersisa</span>
                    </div>

                    
                    <div class="mb-10 text-center">
                        <h3 id="q-text" class="text-2xl md:text-3xl font-bold text-slate-800 leading-snug">Loading...
                        </h3>
                    </div>

                    
                    <div id="options-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- KONFIGURASI GAME ---
        const BASE_SCORE = 500;
        const MAX_BONUS_SCORE = 500;

        const questionsData = <?php echo json_encode($questions, 15, 512) ?>;

        let currentQIndex = 0;
        let totalScore = 0;

        // VARIABEL BARU UNTUK STATISTIK
        let correctCount = 0;
        let wrongCount = 0;

        // Timer Variables
        let timerInterval;
        let timeLeftMs;
        let totalDurationMs;

        function startGame() {
            // Reset Statistik saat mulai
            correctCount = 0;
            wrongCount = 0;
            totalScore = 0;
            currentQIndex = 0;

            document.getElementById('start-screen').classList.add('hidden');
            document.getElementById('game-area').classList.remove('hidden');
            loadQuestion();
        }

        function loadQuestion() {
            if (currentQIndex >= questionsData.length) {
                showResult();
                return;
            }

            const q = questionsData[currentQIndex];

            // 1. Setup UI
            document.getElementById('q-number').innerText = currentQIndex + 1;
            document.getElementById('q-text').innerText = q.question_text;
            document.getElementById('options-container').innerHTML = '';

            // 2. Setup Timer 
            const durationSec = q.duration || 20;
            totalDurationMs = durationSec * 1000;
            timeLeftMs = totalDurationMs;

            updateTimerUI();

            // 3. Render Jawaban
            q.shuffled_answers.forEach((ans, index) => {
                const btn = document.createElement('button');
                btn.className =
                    "option-btn w-full p-5 border-2 border-slate-200 rounded-2xl text-left hover:border-orange-500 hover:bg-orange-50 transition-all duration-200 relative group flex items-center shadow-sm hover:shadow-md";

                const letter = String.fromCharCode(65 + index);

                btn.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center mr-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    ${letter}
                </div>
                <span class="font-semibold text-lg text-slate-700 flex-grow">${ans.answer_text}</span>
            `;

                btn.onclick = () => submitAnswer(ans.is_correct, btn);
                document.getElementById('options-container').appendChild(btn);
            });

            startTimer();
        }

        function startTimer() {
            clearInterval(timerInterval);
            const tickRate = 50;

            timerInterval = setInterval(() => {
                timeLeftMs -= tickRate;
                updateTimerUI();

                if (timeLeftMs <= 0) {
                    timeIsUp();
                }
            }, tickRate);
        }

        function updateTimerUI() {
            const secondsLeft = Math.ceil(timeLeftMs / 1000);
            const textEl = document.getElementById('timer-text');
            textEl.innerText = secondsLeft;

            if (secondsLeft <= 5) {
                textEl.classList.add('text-red-600');
                textEl.classList.remove('text-slate-800');
            } else {
                textEl.classList.remove('text-red-600');
                textEl.classList.add('text-slate-800');
            }

            const percentage = (timeLeftMs / totalDurationMs) * 100;
            const bar = document.getElementById('timer-bar');
            bar.style.width = `${percentage}%`;

            bar.className = `h-2 transition-all duration-100 ease-linear ${
            percentage > 50 ? 'bg-green-500' : 
            percentage > 20 ? 'bg-yellow-400' : 'bg-red-500'
        }`;
        }

        function timeIsUp() {
            clearInterval(timerInterval);
            wrongCount++; // Tambah Salah karena waktu habis

            const allBtns = document.querySelectorAll('.option-btn');
            allBtns.forEach(b => {
                b.disabled = true;
                b.classList.add('opacity-50', 'cursor-not-allowed');
            });

            Swal.fire({
                icon: 'warning',
                title: 'Waktu Habis!',
                text: 'Sayang sekali, poin untuk soal ini 0.',
                timer: 1500,
                showConfirmButton: false,
                backdrop: `rgba(0,0,0,0.4)`
            }).then(() => {
                nextQuestion();
            });
        }

        function submitAnswer(isCorrect, btnElement) {
            clearInterval(timerInterval);

            const allBtns = document.querySelectorAll('.option-btn');
            allBtns.forEach(b => b.disabled = true);

            if (isCorrect) {
                correctCount++; // LOGIKA BARU: Tambah Benar

                const timeRatio = timeLeftMs / totalDurationMs;
                const bonusScore = Math.round(timeRatio * MAX_BONUS_SCORE);
                const questionScore = BASE_SCORE + bonusScore;

                totalScore += questionScore;

                animateValue("current-score", parseInt(document.getElementById('current-score').innerText), totalScore,
                500);

                btnElement.classList.remove('border-slate-200', 'hover:border-orange-500', 'hover:bg-orange-50');
                btnElement.classList.add('bg-green-100', 'border-green-500', 'ring-2', 'ring-green-200');
                btnElement.querySelector('div').classList.add('bg-green-500', 'text-white');

            } else {
                wrongCount++; // LOGIKA BARU: Tambah Salah

                btnElement.classList.remove('border-slate-200', 'hover:border-orange-500', 'hover:bg-orange-50');
                btnElement.classList.add('bg-red-100', 'border-red-500');
                btnElement.querySelector('div').classList.add('bg-red-500', 'text-white');
            }

            setTimeout(() => {
                nextQuestion();
            }, 1500);
        }

        function nextQuestion() {
            currentQIndex++;
            loadQuestion();
        }

        function showResult() {
            document.getElementById('game-area').classList.add('hidden');
            document.getElementById('result-screen').classList.remove('hidden');

            // Update Statistik UI
            document.getElementById('count-correct').innerText = correctCount;
            document.getElementById('count-wrong').innerText = wrongCount;

            animateValue("final-score", 0, totalScore, 1500);

            const maxPossibleScore = questionsData.length * (BASE_SCORE + MAX_BONUS_SCORE);
            const percentage = maxPossibleScore > 0 ? (totalScore / maxPossibleScore) * 100 : 0;
            const iconEl = document.getElementById('score-icon');

            if (percentage >= 80) iconEl.innerText = "👑";
            else if (percentage >= 60) iconEl.innerText = "🔥";
            else if (percentage >= 40) iconEl.innerText = "😎";
            else iconEl.innerText = "🤕";
        }

        function animateValue(id, start, end, duration) {
            if (start === end) return;
            const range = end - start;
            let current = start;
            const increment = end > start ? 10 : -10;
            const stepTime = Math.abs(Math.floor(duration / (range / increment)));

            const obj = document.getElementById(id);
            const timer = setInterval(function() {
                current += increment;
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    current = end;
                    clearInterval(timer);
                }
                obj.innerHTML = current;
            }, stepTime < 10 ? 10 : stepTime);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/tools/minigame.blade.php ENDPATH**/ ?>