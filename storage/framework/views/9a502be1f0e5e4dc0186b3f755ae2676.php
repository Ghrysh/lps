<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#FF6B35">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="LPS Quiz">
    <title>LPS Smart Quiz</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-orange: #FF6B35;
            --primary-orange-dark: #E85A2A;
            --primary-orange-light: #FF8555;
            --accent-amber: #FFA726;
            --accent-yellow: #FFB84D;
            --bg-dark: #0F172A;
            --bg-dark-light: #1E293B;
            --bg-card: #FFFFFF;
            --text-dark: #0F172A;
            --text-light: #FFFFFF;
            --text-muted: #64748B;
            --success: #10B981;
            --error: #EF4444;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.16);
            --shadow-glow: 0 0 24px rgba(255, 107, 53, 0.3);
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100vh;
            background: var(--bg-dark);
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
            padding: env(safe-area-inset-top) env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);
        }
        
        /* Allow text selection for question and answers */
        .question-card h2,
        .answer-card div {
            -webkit-user-select: text;
            user-select: text;
        }
        
        /* MODERN GRADIENT BACKGROUNDS */
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .bg-intro {
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F0 50%, #FFE8DD 100%);
        }
        
        .bg-game {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        }
        
        .bg-result {
            background: linear-gradient(135deg, #FFFFFF 0%, #FFF5F0 50%, #FFE8DD 100%);
        }
        
        /* GLASS MORPHISM */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 107, 53, 0.15);
        }
        
        .glass-white {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* FLOATING ELEMENTS */
        @keyframes floatSoft {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        
        .float-element {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.08), rgba(255, 167, 38, 0.08));
            pointer-events: none;
        }
        
        .float-element:nth-child(1) { 
            width: 200px; height: 200px; 
            top: 10%; left: -50px; 
            animation: floatSoft 8s ease-in-out infinite; 
        }
        .float-element:nth-child(2) { 
            width: 150px; height: 150px; 
            top: 60%; right: -30px; 
            animation: floatSoft 10s ease-in-out infinite 1s; 
        }
        .float-element:nth-child(3) { 
            width: 180px; height: 180px; 
            bottom: 5%; left: 40%; 
            animation: floatSoft 12s ease-in-out infinite 2s; 
        }
        
        /* ANIMATIONS */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 107, 53, 0.3); }
            50% { box-shadow: 0 0 32px rgba(255, 107, 53, 0.5); }
        }
        
        @keyframes progressFlow {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        
        .fade-in-up { animation: fadeInUp 0.5s ease-out; }
        .fade-in-down { animation: fadeInDown 0.5s ease-out; }
        .scale-in { animation: scaleIn 0.4s ease-out; }
        .pulse-glow { animation: pulseGlow 2s ease-in-out infinite; }
        
        /* TYPOGRAPHY */
        .title-display {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            background: linear-gradient(135deg, #FF6B35 0%, #FFA726 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }
        
        .text-gradient-orange {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--accent-amber) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* BUTTON STYLES */
        .btn-primary {
            position: relative;
            padding: 1.25rem 3rem;
            font-family: 'Poppins', sans-serif;
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--accent-amber) 100%);
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-md), var(--shadow-glow);
            overflow: hidden;
            min-height: 48px;
            touch-action: manipulation;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg), 0 0 32px rgba(255, 107, 53, 0.4);
        }
        
        .btn-primary:hover::before {
            transform: translateX(100%);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        /* ANSWER CARDS */
        .answer-card {
            position: relative;
            background: var(--bg-card);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            box-shadow: var(--shadow-sm);
            min-height: 64px;
            display: flex;
            align-items: center;
            touch-action: manipulation;
        }
        
        .answer-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary-orange);
            box-shadow: var(--shadow-md), 0 0 0 4px rgba(255, 107, 53, 0.1);
        }
        
        .answer-card:active {
            transform: translateY(-2px);
        }
        
        .answer-letter {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            transition: all 0.3s;
            flex-shrink: 0;
        }
        
        .answer-card:hover .answer-letter {
            transform: scale(1.1);
        }
        
        /* TIMER CIRCLE */
        .timer-ring {
            position: relative;
            width: 100px;
            height: 100px;
        }
        
        .timer-ring svg {
            transform: rotate(-90deg);
        }
        
        .timer-ring circle {
            fill: none;
            stroke-width: 6;
        }
        
        .timer-background {
            stroke: rgba(255, 107, 53, 0.15);
        }
        
        .timer-progress {
            stroke: var(--primary-orange);
            stroke-linecap: round;
            transition: stroke-dashoffset 0.05s linear;
        }
        
        .timer-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-orange);
        }
        
        /* HUD ELEMENTS */
        .hud-badge {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 107, 53, 0.2);
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .hud-badge i {
            color: var(--primary-orange);
        }
        
        /* PROGRESS BAR */
        .progress-container {
            height: 8px;
            background: rgba(255, 107, 53, 0.1);
            border-radius: 999px;
            overflow: hidden;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success) 0%, #34D399 100%);
            background-size: 200% 100%;
            border-radius: 999px;
            transition: width 0.1s linear;
            position: relative;
            animation: progressFlow 2s linear infinite;
        }
        
        .progress-fill.warning {
            background: linear-gradient(90deg, var(--accent-amber) 0%, var(--accent-yellow) 100%);
            background-size: 200% 100%;
        }
        
        .progress-fill.danger {
            background: linear-gradient(90deg, var(--error) 0%, #F87171 100%);
            background-size: 200% 100%;
        }
        
        /* QUESTION CARD */
        .question-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 107, 53, 0.15);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }
        
        .question-card h2 {
            color: var(--text-dark);
        }
        
        /* STATS CARD */
        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 107, 53, 0.15);
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-value {
            color: var(--text-dark);
        }
        
        .stat-label {
            color: var(--text-muted);
        }
        
        /* ICON BADGE */
        .icon-badge {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FF6B35, #FFA726);
            backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 107, 53, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        /* SOUND TOGGLE */
        .sound-toggle {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 1000;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 107, 53, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: var(--shadow-sm);
        }
        
        .sound-toggle:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 1);
            border-color: var(--primary-orange);
        }
        
        .sound-toggle i {
            color: var(--primary-orange);
        }
        
        /* CONFETTI */
        @keyframes confettiFall {
            0% { 
                transform: translateY(-100vh) rotate(0deg); 
                opacity: 1; 
            }
            100% { 
                transform: translateY(100vh) rotate(720deg); 
                opacity: 0; 
            }
        }
        
        .confetti {
            position: fixed;
            width: 8px;
            height: 8px;
            z-index: 9999;
            animation: confettiFall 3s linear forwards;
            border-radius: 2px;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .title-display { font-size: clamp(2rem, 12vw, 3.5rem); }
            .btn-primary { padding: 1rem 2rem; font-size: 1rem; }
            .answer-card { padding: 1rem; }
            .answer-letter { width: 40px; height: 40px; font-size: 1rem; }
            .timer-ring { width: 80px; height: 80px; }
            .timer-text { font-size: 1.5rem; }
            .question-card { padding: 1.5rem; }
            .hud-badge { padding: 0.625rem 1rem; font-size: 0.875rem; }
        }
        
        /* PORTRAIT ORIENTATION - MOBILE */
        @media (max-width: 768px) and (orientation: portrait) {
            .title-display { 
                font-size: clamp(2rem, 10vw, 3rem);
                line-height: 1.1;
            }
            
            .btn-primary { 
                padding: 0.875rem 1.75rem; 
                font-size: 0.95rem;
                width: 100%;
                max-width: 280px;
            }
            
            .glass {
                padding: 1rem 1.25rem;
            }
            
            .icon-badge {
                width: 70px;
                height: 70px;
            }
            
            .icon-badge i {
                font-size: 2rem !important;
            }
            
            /* Game Screen Portrait */
            #game-screen .hud-badge {
                padding: 0.5rem 0.875rem;
                font-size: 0.8rem;
                gap: 0.375rem;
            }
            
            .timer-ring {
                width: 70px;
                height: 70px;
            }
            
            .timer-text {
                font-size: 1.25rem;
            }
            
            .question-card {
                padding: 1.25rem;
            }
            
            .question-card h2 {
                font-size: 1.125rem;
                line-height: 1.4;
            }
            
            .answer-card {
                padding: 0.875rem 1rem;
            }
            
            .answer-letter {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
            
            .answer-card div > div:last-child {
                font-size: 0.9rem !important;
                line-height: 1.3;
            }
            
            /* Result Screen Portrait */
            #result-screen .icon-badge {
                width: 90px;
                height: 90px;
            }
            
            #result-screen .icon-badge i {
                font-size: 2.5rem !important;
            }
            
            #result-screen h2 {
                font-size: 1.75rem;
            }
            
            #final-score {
                font-size: 3.5rem !important;
            }
            
            .stat-card {
                padding: 1rem;
            }
            
            .stat-value {
                font-size: 2rem !important;
            }
            
            .stat-label {
                font-size: 0.75rem !important;
            }
        }
        
        /* PORTRAIT ORIENTATION - TABLET */
        @media (min-width: 769px) and (max-width: 1024px) and (orientation: portrait) {
            .title-display { 
                font-size: clamp(3rem, 8vw, 4rem);
            }
            
            .btn-primary { 
                padding: 1.125rem 2.5rem; 
                font-size: 1.125rem;
            }
            
            .question-card h2 {
                font-size: 1.75rem;
            }
            
            .answer-card {
                padding: 1.125rem 1.25rem;
            }
            
            .answer-letter {
                width: 44px;
                height: 44px;
                font-size: 1.125rem;
            }
            
            .timer-ring {
                width: 90px;
                height: 90px;
            }
            
            .timer-text {
                font-size: 1.75rem;
            }
        }
        
        /* LANDSCAPE ORIENTATION - MOBILE */
        @media (max-height: 500px) and (orientation: landscape) {
            .title-display { 
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
            
            .icon-badge {
                width: 60px;
                height: 60px;
                margin-bottom: 0.5rem;
            }
            
            .icon-badge i {
                font-size: 1.75rem !important;
            }
            
            .glass {
                padding: 0.75rem 1rem;
            }
            
            .btn-primary {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
            
            #intro-screen > div,
            #result-screen > div {
                max-height: 100vh;
                overflow-y: auto;
                padding: 1rem 0;
            }
            
            /* Game Screen Landscape */
            #game-screen .p-4,
            #game-screen .p-6 {
                padding: 0.75rem;
            }
            
            .timer-ring {
                width: 60px;
                height: 60px;
                margin-bottom: 0.5rem;
            }
            
            .timer-text {
                font-size: 1.125rem;
            }
            
            .question-card {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
            
            .question-card h2 {
                font-size: 1rem;
            }
            
            .answer-card {
                padding: 0.625rem 0.75rem;
            }
            
            .answer-letter {
                width: 32px;
                height: 32px;
                font-size: 0.875rem;
            }
            
            .hud-badge {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }
        }
        
        /* SMALL SCREENS */
        @media (max-width: 360px) {
            .title-display {
                font-size: 1.75rem;
            }
            
            .btn-primary {
                padding: 0.875rem 1.5rem;
                font-size: 0.875rem;
            }
            
            .question-card h2 {
                font-size: 1rem;
            }
            
            .answer-card div > div:last-child {
                font-size: 0.8rem !important;
            }
            
            .glass {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }
        
        /* EXTRA LARGE SCREENS */
        @media (min-width: 1920px) {
            .title-display {
                font-size: 6rem;
            }
            
            .question-card {
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }
            
            #answers-container > div {
                max-width: 1200px;
            }
        }
        
        /* HIDE SCROLLBAR */
        ::-webkit-scrollbar { display: none; }
        * { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* UTILITY */
        .text-shadow-sm { text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); }
    </style>
</head>
<body>

    <!-- SOUND TOGGLE -->
    <div class="sound-toggle" onclick="toggleSound()" id="sound-toggle">
        <i class="fas fa-volume-up text-lg" id="sound-icon"></i>
    </div>

    <!-- ==================== INTRO SCREEN ==================== -->
    <div id="intro-screen" class="bg-intro fixed inset-0 flex items-center justify-center overflow-hidden">
        <!-- Floating Elements -->
        <div class="float-element"></div>
        <div class="float-element"></div>
        <div class="float-element"></div>
        
        <div class="text-center z-10 px-6 max-w-2xl mx-auto">
            <!-- Icon Badge -->
            <div class="scale-in mb-8">
                <div class="icon-badge pulse-glow mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, #FF6B35, #FFA726); border-color: rgba(255, 107, 53, 0.3);">
                    <i class="fas fa-brain text-5xl text-white"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h1 class="title-display fade-in-down mb-4 text-shadow-sm">
                LPS SMART QUIZ
            </h1>
            
            <!-- Subtitle -->
            <p class="text-slate-700 text-lg md:text-xl font-medium mb-10 fade-in-up" style="animation-delay: 0.1s;">
                Test your knowledge and compete for the highest score
            </p>
            
            <!-- Stats -->
            <div class="flex justify-center gap-4 mb-12 fade-in-up" style="animation-delay: 0.2s;">
                <div class="glass rounded-2xl px-6 py-4 min-w-[120px] border-2 border-orange-200">
                    <div class="text-gradient-orange text-3xl font-bold mb-1"><?php echo e(count($questions)); ?></div>
                    <div class="text-slate-600 text-sm font-medium">Questions</div>
                </div>
                <div class="glass rounded-2xl px-6 py-4 min-w-[120px] border-2 border-orange-200">
                    <div class="text-gradient-orange text-3xl font-bold mb-1">10</div>
                    <div class="text-slate-600 text-sm font-medium">Points Each</div>
                </div>
            </div>
            
            <!-- Start Button -->
            <button onclick="startGame()" class="btn-primary fade-in-up" style="animation-delay: 0.3s;">
                <span class="relative z-10 flex items-center gap-3 justify-center">
                    <i class="fas fa-play"></i>
                    Start Quiz
                </span>
            </button>
        </div>
    </div>

    <!-- ==================== GAME SCREEN ==================== -->
    <div id="game-screen" class="bg-game fixed inset-0 hidden flex-col overflow-hidden">
        <!-- Floating Elements -->
        <div class="float-element"></div>
        <div class="float-element"></div>
        <div class="float-element"></div>
        
        <!-- Top HUD -->
        <div class="relative z-10 p-4 md:p-6 flex justify-between items-center">
            <!-- Question Counter -->
            <div class="hud-badge fade-in-down">
                <i class="fas fa-list-ol"></i>
                <span class="text-lg font-bold">
                    <span id="q-number">1</span>/<span><?php echo e(count($questions)); ?></span>
                </span>
            </div>
            
            <!-- Score -->
            <div class="hud-badge fade-in-down" style="animation-delay: 0.1s;">
                <i class="fas fa-trophy text-amber-300"></i>
                <span class="text-lg font-bold" id="current-score">0</span>
            </div>
        </div>
        
        <!-- Timer -->
        <div class="flex justify-center mb-6 md:mb-8 scale-in">
            <div class="timer-ring">
                <svg width="100" height="100">
                    <circle class="timer-background" cx="50" cy="50" r="44"></circle>
                    <circle class="timer-progress" cx="50" cy="50" r="44" id="timer-circle"></circle>
                </svg>
                <div class="timer-text" id="timer-text">20</div>
            </div>
        </div>
        
        <!-- Question Card -->
        <div class="px-4 md:px-8 mb-6 md:mb-8 fade-in-up" style="animation-delay: 0.1s;">
            <div class="max-w-4xl mx-auto question-card">
                <h2 id="question-text" class="text-xl md:text-3xl font-bold leading-tight text-center">
                    Loading question...
                </h2>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="px-4 md:px-8 mb-6 md:mb-8">
            <div class="max-w-4xl mx-auto progress-container">
                <div id="progress-bar" class="progress-fill"></div>
            </div>
        </div>
        
        <!-- Answers -->
        <div id="answers-container" class="px-4 md:px-8 pb-8 flex-1 overflow-y-auto" style="-webkit-overflow-scrolling: touch;">
            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                <!-- Answers will be injected here -->
            </div>
        </div>
    </div>

    <!-- ==================== RESULT SCREEN ==================== -->
    <div id="result-screen" class="bg-result fixed inset-0 hidden items-center justify-center overflow-hidden">
        <!-- Floating Elements -->
        <div class="float-element"></div>
        <div class="float-element"></div>
        <div class="float-element"></div>
        
        <div class="text-center z-10 px-6 max-w-2xl mx-auto">
            <!-- Icon -->
            <div class="scale-in mb-8">
                <div class="icon-badge pulse-glow mx-auto" style="width: 120px; height: 120px; background: linear-gradient(135deg, #FF6B35, #FFA726); border-color: rgba(255, 107, 53, 0.3);" id="result-icon-container">
                    <i id="result-icon" class="fas fa-trophy text-6xl text-white"></i>
                </div>
            </div>
            
            <!-- Title -->
            <h2 class="text-slate-800 text-3xl md:text-5xl font-black mb-6 fade-in-down">
                Quiz Complete!
            </h2>
            
            <!-- Final Score -->
            <div class="mb-10 fade-in-up" style="animation-delay: 0.1s;">
                <p class="text-slate-700 text-sm md:text-base font-semibold mb-3 uppercase tracking-wider">
                    Final Score
                </p>
                <div class="glass rounded-3xl px-10 py-6 inline-block pulse-glow border-2 border-orange-200">
                    <div id="final-score" class="text-gradient-orange text-6xl md:text-8xl font-black">
                        0
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-10 fade-in-up" style="animation-delay: 0.2s;">
                <!-- Correct -->
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-3 border-2 border-green-500/30">
                        <i class="fas fa-check text-xl text-green-600"></i>
                    </div>
                    <div id="count-correct" class="stat-value text-3xl md:text-4xl font-bold mb-1">0</div>
                    <div class="stat-label text-sm font-medium">Correct</div>
                </div>
                
                <!-- Wrong -->
                <div class="stat-card">
                    <div class="w-12 h-12 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-3 border-2 border-red-500/30">
                        <i class="fas fa-times text-xl text-red-600"></i>
                    </div>
                    <div id="count-wrong" class="stat-value text-3xl md:text-4xl font-bold mb-1">0</div>
                    <div class="stat-label text-sm font-medium">Wrong</div>
                </div>
            </div>
            
            <!-- Replay Button -->
            <button onclick="location.reload()" class="btn-primary fade-in-up" style="animation-delay: 0.3s;">
                <span class="relative z-10 flex items-center gap-3 justify-center">
                    <i class="fas fa-redo-alt"></i>
                    Play Again
                </span>
            </button>
        </div>
    </div>

    <script>
        // ==================== AUDIO SYSTEM ====================
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const audioContext = new AudioContext();
        let soundEnabled = true;
        
        // Sound Toggle
        function toggleSound() {
            soundEnabled = !soundEnabled;
            const icon = document.getElementById('sound-icon');
            
            if (soundEnabled) {
                icon.className = 'fas fa-volume-up text-lg';
            } else {
                icon.className = 'fas fa-volume-mute text-lg';
            }
        }
        
        function playNote(freq, duration, type = 'sine', volume = 0.08) {
            if (!soundEnabled) return;
            
            const osc = audioContext.createOscillator();
            const gain = audioContext.createGain();
            
            osc.connect(gain);
            gain.connect(audioContext.destination);
            
            osc.frequency.value = freq;
            osc.type = type;
            
            gain.gain.setValueAtTime(volume, audioContext.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + duration);
            
            osc.start(audioContext.currentTime);
            osc.stop(audioContext.currentTime + duration);
        }
        
        // Sound Effects - Modern UI sounds
        function playClickSound() {
            playNote(800, 0.05, 'sine', 0.1);
        }
        
        function playCorrectSound() {
            playNote(659.25, 0.1, 'sine', 0.12);
            setTimeout(() => playNote(1046.5, 0.2, 'sine', 0.14), 100);
        }
        
        function playWrongSound() {
            playNote(330, 0.15, 'sine', 0.15);
            setTimeout(() => playNote(220, 0.25, 'sine', 0.15), 150);
        }
        
        function playTimeUpSound() {
            for(let i = 0; i < 3; i++) {
                setTimeout(() => playNote(400 - (i * 50), 0.15, 'sine', 0.12), i * 120);
            }
        }
        
        function playTransitionSound() {
            playNote(523.25, 0.08, 'sine', 0.1);
            setTimeout(() => playNote(659.25, 0.08, 'sine', 0.1), 60);
        }
        
        // ==================== GAME LOGIC ====================
        const SCORE_PER_QUESTION = 10;
        const questionsData = <?php echo json_encode($questions, 15, 512) ?>;
        
        let currentQIndex = 0;
        let totalScore = 0;
        let correctCount = 0;
        let wrongCount = 0;
        let timerInterval;
        let timeLeftMs;
        let totalDurationMs;
        
        const circumference = 2 * Math.PI * 44;
        
        // Start Game
        function startGame() {
            playClickSound();
            playTransitionSound();
            
            // Reset
            currentQIndex = 0;
            totalScore = 0;
            correctCount = 0;
            wrongCount = 0;
            document.getElementById('current-score').innerText = '0';
            
            // Transition
            const intro = document.getElementById('intro-screen');
            intro.style.opacity = '0';
            intro.style.transform = 'scale(0.95)';
            intro.style.transition = 'all 0.4s ease-out';
            
            setTimeout(() => {
                intro.classList.add('hidden');
                document.getElementById('game-screen').classList.remove('hidden');
                document.getElementById('game-screen').classList.add('flex');
                
                loadQuestion();
            }, 400);
        }
        
        // Load Question
        function loadQuestion() {
            if (currentQIndex >= questionsData.length) {
                showResult();
                return;
            }
            
            const q = questionsData[currentQIndex];
            
            document.getElementById('q-number').innerText = currentQIndex + 1;
            document.getElementById('question-text').innerText = q.question_text;
            
            // Setup timer
            const duration = q.duration || 20;
            totalDurationMs = duration * 1000;
            timeLeftMs = totalDurationMs;
            
            const timerCircle = document.getElementById('timer-circle');
            timerCircle.style.strokeDasharray = circumference;
            timerCircle.style.strokeDashoffset = 0;
            
            updateTimer();
            
            // Render answers
            const container = document.getElementById('answers-container');
            container.innerHTML = '<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4"></div>';
            const grid = container.querySelector('div');
            
            const colors = [
                {bg: 'bg-gradient-to-br from-red-500 to-red-600', text: 'text-slate-800'},
                {bg: 'bg-gradient-to-br from-blue-500 to-blue-600', text: 'text-slate-800'},
                {bg: 'bg-gradient-to-br from-green-500 to-green-600', text: 'text-slate-800'},
                {bg: 'bg-gradient-to-br from-amber-500 to-amber-600', text: 'text-slate-800'}
            ];
            
            q.shuffled_answers.forEach((ans, idx) => {
                const letter = String.fromCharCode(65 + idx);
                const color = colors[idx];
                
                const card = document.createElement('div');
                card.className = 'answer-card fade-in-up';
                card.style.animationDelay = `${idx * 0.05}s`;
                card.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="answer-letter ${color.bg} text-white shadow-md">
                            ${letter}
                        </div>
                        <div class="${color.text} text-base md:text-lg font-semibold flex-1">
                            ${ans.answer_text}
                        </div>
                    </div>
                `;
                
                card.onclick = () => {
                    playClickSound();
                    submitAnswer(ans.is_correct, card);
                };
                
                grid.appendChild(card);
            });
            
            startTimer();
        }
        
        // Timer
        function startTimer() {
            clearInterval(timerInterval);
            
            timerInterval = setInterval(() => {
                timeLeftMs -= 50;
                updateTimer();
                
                if (timeLeftMs <= 0) {
                    timeIsUp();
                }
            }, 50);
        }
        
        function updateTimer() {
            const seconds = Math.ceil(timeLeftMs / 1000);
            document.getElementById('timer-text').innerText = seconds;
            
            const percentage = timeLeftMs / totalDurationMs;
            const offset = circumference * (1 - percentage);
            const timerCircle = document.getElementById('timer-circle');
            timerCircle.style.strokeDashoffset = offset;
            
            // Change color based on time
            if (percentage <= 0.25) {
                timerCircle.style.stroke = '#EF4444';
            } else if (percentage <= 0.5) {
                timerCircle.style.stroke = '#FFA726';
            } else {
                timerCircle.style.stroke = '#FF6B35';
            }
            
            // Progress bar
            const bar = document.getElementById('progress-bar');
            bar.style.width = `${percentage * 100}%`;
            
            if (percentage > 0.5) {
                bar.className = 'progress-fill';
            } else if (percentage > 0.25) {
                bar.className = 'progress-fill warning';
            } else {
                bar.className = 'progress-fill danger';
            }
        }
        
        function timeIsUp() {
            clearInterval(timerInterval);
            wrongCount++;
            playTimeUpSound();
            
            document.querySelectorAll('.answer-card').forEach(card => {
                card.style.opacity = '0.5';
                card.style.pointerEvents = 'none';
            });
            
            Swal.fire({
                icon: 'warning',
                title: 'Time Up!',
                text: 'No points awarded',
                timer: 1500,
                showConfirmButton: false,
                background: 'linear-gradient(135deg, #FF6B35 0%, #FFA726 100%)',
                color: '#fff',
                customClass: { popup: 'scale-in' }
            }).then(() => {
                nextQuestion();
            });
        }
        
        // Submit Answer
        function submitAnswer(isCorrect, cardElement) {
            clearInterval(timerInterval);
            
            document.querySelectorAll('.answer-card').forEach(card => {
                card.style.pointerEvents = 'none';
            });
            
            if (isCorrect) {
                correctCount++;
                totalScore += SCORE_PER_QUESTION;
                playCorrectSound();
                
                cardElement.style.background = 'linear-gradient(135deg, #10B981 0%, #059669 100%)';
                cardElement.style.borderColor = '#10B981';
                cardElement.style.transform = 'translateY(-4px) scale(1.02)';
                cardElement.style.boxShadow = '0 8px 32px rgba(16, 185, 129, 0.3)';
                
                const letter = cardElement.querySelector('.answer-letter');
                letter.style.background = '#FFFFFF';
                letter.style.color = '#10B981';
                letter.innerHTML = '<i class="fas fa-check"></i>';
                
                const text = cardElement.querySelector('div > div:last-child');
                text.style.color = '#FFFFFF';
                
                animateScore();
                createParticles(cardElement, '#10B981');
                
            } else {
                wrongCount++;
                playWrongSound();
                
                cardElement.style.background = 'linear-gradient(135deg, #EF4444 0%, #DC2626 100%)';
                cardElement.style.borderColor = '#EF4444';
                cardElement.style.boxShadow = '0 8px 32px rgba(239, 68, 68, 0.3)';
                
                const letter = cardElement.querySelector('.answer-letter');
                letter.style.background = '#FFFFFF';
                letter.style.color = '#EF4444';
                letter.innerHTML = '<i class="fas fa-times"></i>';
                
                const text = cardElement.querySelector('div > div:last-child');
                text.style.color = '#FFFFFF';
            }
            
            setTimeout(() => {
                nextQuestion();
            }, 1500);
        }
        
        function nextQuestion() {
            playTransitionSound();
            
            const container = document.getElementById('answers-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(20px)';
            container.style.transition = 'all 0.3s ease-out';
            
            setTimeout(() => {
                currentQIndex++;
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
                loadQuestion();
            }, 300);
        }
        
        function animateScore() {
            const scoreEl = document.getElementById('current-score');
            const start = parseInt(scoreEl.innerText);
            const end = totalScore;
            const duration = 400;
            const range = end - start;
            const increment = range / (duration / 20);
            
            let current = start;
            const timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    current = end;
                    clearInterval(timer);
                }
                scoreEl.innerText = Math.floor(current);
            }, 20);
        }
        
        // Show Result
        function showResult() {
            playTransitionSound();
            
            const game = document.getElementById('game-screen');
            game.style.opacity = '0';
            game.style.transform = 'scale(0.95)';
            game.style.transition = 'all 0.4s ease-out';
            
            setTimeout(() => {
                game.classList.add('hidden');
                game.classList.remove('flex');
                
                const result = document.getElementById('result-screen');
                result.classList.remove('hidden');
                result.classList.add('flex');
                
                document.getElementById('count-correct').innerText = correctCount;
                document.getElementById('count-wrong').innerText = wrongCount;
                
                // Animate final score
                const finalScoreEl = document.getElementById('final-score');
                let current = 0;
                const timer = setInterval(() => {
                    current += Math.ceil(totalScore / 40);
                    if (current >= totalScore) {
                        current = totalScore;
                        clearInterval(timer);
                    }
                    finalScoreEl.innerText = current;
                }, 30);
                
                // Set icon
                const percentage = (totalScore / (questionsData.length * SCORE_PER_QUESTION)) * 100;
                const icon = document.getElementById('result-icon');
                
                if (percentage >= 80) {
                    icon.className = 'fas fa-trophy text-6xl text-yellow-300';
                    createConfetti(40);
                } else if (percentage >= 60) {
                    icon.className = 'fas fa-star text-6xl text-white';
                    createConfetti(20);
                } else if (percentage >= 40) {
                    icon.className = 'fas fa-medal text-6xl text-white';
                } else {
                    icon.className = 'fas fa-redo text-6xl text-white';
                }
            }, 400);
        }
        
        // Visual Effects
        function createParticles(element, color) {
            const rect = element.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            for (let i = 0; i < 12; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.width = '6px';
                particle.style.height = '6px';
                particle.style.background = color;
                particle.style.borderRadius = '50%';
                particle.style.left = centerX + 'px';
                particle.style.top = centerY + 'px';
                particle.style.zIndex = '9999';
                particle.style.pointerEvents = 'none';
                
                document.body.appendChild(particle);
                
                const angle = (Math.PI * 2 * i) / 12;
                const velocity = 60 + Math.random() * 30;
                const tx = Math.cos(angle) * velocity;
                const ty = Math.sin(angle) * velocity;
                
                particle.animate([
                    { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                    { transform: `translate(${tx}px, ${ty}px) scale(0)`, opacity: 0 }
                ], {
                    duration: 600,
                    easing: 'cubic-bezier(0, .9, .57, 1)'
                }).onfinish = () => particle.remove();
            }
        }
        
        function createConfetti(count) {
            const colors = ['#FF6B35', '#FFA726', '#FFB84D', '#10B981', '#3B82F6', '#8B5CF6'];
            
            for (let i = 0; i < count; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * window.innerWidth + 'px';
                    confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.animationDuration = (2.5 + Math.random() * 1.5) + 's';
                    confetti.style.animationDelay = Math.random() * 0.3 + 's';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 4500);
                }, i * 30);
            }
        }
        
        // Initialize
        window.addEventListener('load', () => {
            document.body.addEventListener('click', function initAudio() {
                if (audioContext.state === 'suspended') {
                    audioContext.resume();
                }
                document.body.removeEventListener('click', initAudio);
            }, { once: true });
        });
    </script>
</body>
</html><?php /**PATH /var/www/html/resources/views/admin/tools/minigame.blade.php ENDPATH**/ ?>