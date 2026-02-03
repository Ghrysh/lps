<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>

    {{-- CDN Tailwind & Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    {{-- Font: Plus Jakarta Sans (Agar selaras dengan Dashboard Baru) --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Alpine JS & Chart JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc; /* Slate-50 */
            overflow-x: hidden;
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar Transition */
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

        /* Active Menu Style (Emerald Theme) */
        .sidebar-active {
            background-color: #ecfdf5; /* emerald-50 */
            color: #059669; /* emerald-600 */
            font-weight: 600;
            border-right: 3px solid #059669; /* emerald-600 */
        }

        /* Dropdown Animation */
        .dropdown-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .dropdown-container.open {
            max-height: 500px;
        }
        
        /* Arrow Rotation */
        .dropdown-active i.fa-chevron-right {
            transform: rotate(90deg);
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen text-slate-600">

    {{-- Overlay untuk Mobile --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm" onclick="toggleSidebar()"></div>

    {{-- SIDEBAR (Updated to White/Emerald Theme) --}}
    <aside id="sidebar"
        class="sidebar-transition w-64 bg-white border-r border-slate-200 fixed h-full z-50 -translate-x-full lg:translate-x-0 flex flex-col shadow-sm">
        
        {{-- 1. Sidebar Header (Logo) --}}
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-600 text-white p-2 rounded-lg shadow-sm shadow-emerald-200">
                    <i class="fas fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800 text-lg leading-tight tracking-tight">LPS Admin</h1>
                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Panel Admin</p>
                </div>
            </div>
            {{-- Close Button (Mobile) --}}
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- 2. Navigation Menu --}}
        <nav class="flex-grow py-6 overflow-y-auto px-4 space-y-1">
            
            {{-- Label --}}
            <div class="px-4 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Main Menu</div>

            {{-- Dashboard Link --}}
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.analytics') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} flex items-center px-4 py-2.5 text-sm rounded-lg transition-all group">
                <i class="{{ request()->routeIs('admin.dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600' }} fas fa-home w-5 transition-colors mr-3"></i>
                <span>Dashboard</span>
            </a>

            {{-- Label --}}
            <div class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aplikasi</div>

            {{-- Dropdown Apps --}}
            <div>
                <button onclick="toggleDropdown('masterDropdown')" id="btn-masterDropdown"
                    class="{{ request()->is('admin/tools*') || request()->is('admin/gallery*') || request()->is('admin/scanner*') ? 'text-slate-800 font-medium bg-slate-50' : 'text-slate-500' }} w-full flex items-center justify-between px-4 py-2.5 text-sm hover:bg-slate-50 rounded-lg transition group">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group w-5 mr-3 text-slate-400 group-hover:text-slate-600"></i>
                        <span>Apps & Tools</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 transition-transform duration-200"></i>
                </button>

                {{-- Sub Menu Container --}}
                <div id="masterDropdown"
                    class="dropdown-container pl-4 space-y-1 mt-1 {{ request()->routeIs('admin.gallery.*') || request()->routeIs('admin.scanner') || request()->routeIs('admin.photobooth') || request()->routeIs('tools.*') ? 'open' : '' }}">

                    <a href="{{ route('admin.gallery.index') }}"
                        class="{{ request()->routeIs('admin.gallery.*') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800' }} block px-4 py-2 text-sm rounded-lg transition border-l-2 {{ request()->routeIs('admin.gallery.*') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300' }}">
                        <span>Gallery Image</span>
                    </a>

                    <a href="{{ route('admin.scanner') }}"
                        class="{{ request()->routeIs('admin.scanner') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800' }} block px-4 py-2 text-sm rounded-lg transition border-l-2 {{ request()->routeIs('admin.scanner') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300' }}">
                        <span>Scan Object AR</span>
                    </a>

                    <a href="{{ route('admin.points.scan') }}"
                        class="{{ request()->routeIs('admin.points.scan') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800' }} block px-4 py-2 text-sm rounded-lg transition border-l-2 {{ request()->routeIs('admin.points.scan') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300' }}">
                        <span>Scan QR Code</span>
                    </a>

                    <a href="{{ route('admin.photobooth') }}"
                        class="{{ request()->routeIs('admin.photobooth') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800' }} block px-4 py-2 text-sm rounded-lg transition border-l-2 {{ request()->routeIs('admin.photobooth') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300' }}">
                        <span>Photobooth AI</span>
                    </a>

                    {{-- Divider Kecil --}}
                    <div class="border-t border-slate-100 my-2 mx-4"></div>

                    <a href="{{ route('tools.quiz_manager') }}"
                        class="{{ request()->routeIs('tools.quiz_manager') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800' }} block px-4 py-2 text-sm rounded-lg transition border-l-2 {{ request()->routeIs('tools.quiz_manager') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300' }}">
                        <span>Quiz Manager</span>
                    </a>

                    <a href="{{ route('tools.minigame') }}"
                        class="{{ request()->routeIs('tools.minigame') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800' }} block px-4 py-2 text-sm rounded-lg transition border-l-2 {{ request()->routeIs('tools.minigame') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300' }}">
                        <span>LPS Smart Quiz</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- 3. Sidebar Footer (Profile) --}}
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    {{-- Avatar --}}
                    <div class="w-9 h-9 flex-shrink-0 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-sm font-bold text-emerald-700">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-slate-700 truncate leading-none mb-0.5">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">Administrator</p>
                    </div>
                </div>
                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    <div id="mainContent" class="sidebar-transition flex-col min-h-screen lg:ml-64 flex">

        {{-- === BAGIAN HEADER YANG DIMINTA TETAP ADA (MOBILE) === --}}
        <header id="mobileHeader"
            class="bg-white shadow-sm border-b p-4 flex items-center justify-between lg:hidden shrink-0">
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="text-orange-600 p-2 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="ml-3 font-bold text-gray-800">LPS</h2>
            </div>

            <div class="flex items-center gap-2 text-orange-600 font-semibold text-sm">
                <i class="fas fa-coins"></i>
                {{ number_format($totalPoints ?? 0) }}
            </div>
        </header>

        {{-- === BAGIAN HEADER YANG DIMINTA TETAP ADA (DESKTOP) === --}}
        <header id="desktopHeader"
            class="bg-white shadow-sm border-b px-8 py-4 hidden lg:flex items-center justify-between sticky top-0 z-30 sidebar-transition shrink-0">

            {{-- KIRI --}}
            <div class="flex items-center">
                <button id="desktopHamburger" onclick="toggleSidebar()"
                    class="text-orange-600 p-2 focus:outline-none mr-4 hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="font-bold text-[#112D4E]">Lembaga Penjamin Simpanan</h2>
            </div>

            {{-- KANAN : USER & POINT --}}
            <div class="flex items-center gap-4">

                {{-- POINT BADGE --}}
                <div
                    class="flex items-center gap-2 bg-orange-50 text-orange-600 px-4 py-2 rounded-full text-sm font-semibold shadow-sm">
                    <i class="fas fa-coins"></i>
                    {{ number_format($totalPoints ?? 0) }} Poin
                </div>

                {{-- USER --}}
                <div class="flex items-center gap-3">
                    <div class="text-right leading-tight">
                        <p class="text-sm font-semibold text-gray-800">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[11px] text-gray-400">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    {{-- AVATAR --}}
                    <div
                        class="w-9 h-9 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold uppercase">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="p-4 md:p-6 lg:p-8 flex-grow bg-[#f8fafc]">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="p-6 text-center text-[10px] text-slate-400 uppercase tracking-widest shrink-0 bg-[#f8fafc]">
            © 2026 LPS - Lembaga Penjamin Simpanan
        </footer>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // Dropdown Logic
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const btn = document.getElementById('btn-' + id);
            
            // Toggle Height
            dropdown.classList.toggle('open');
            
            // Toggle Active State Style
            btn.classList.toggle('dropdown-active');
            if (dropdown.classList.contains('open')) {
                btn.classList.add('text-slate-800', 'bg-slate-50');
                btn.classList.remove('text-slate-500');
            } else {
                // Only remove if not in active route group (simplified logic)
                // Cek apakah tombol sedang aktif secara route atau tidak
                if(!btn.classList.contains('bg-slate-50')) { // simplified check
                     btn.classList.remove('text-slate-800', 'bg-slate-50');
                     btn.classList.add('text-slate-500');
                }
            }

            // Rotate Arrow
            const icon = btn.querySelector('.fa-chevron-right');
            if (dropdown.classList.contains('open')) {
                icon.style.transform = 'rotate(90deg)';
                icon.classList.add('text-slate-600');
                icon.classList.remove('text-slate-300');
            } else {
                icon.style.transform = 'rotate(0deg)';
                icon.classList.remove('text-slate-600');
                icon.classList.add('text-slate-300');
            }
        }

        // Sidebar Toggle Logic
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const desktopHamburger = document.getElementById('desktopHamburger');
            const body = document.body;

            // Check current state (Desktop vs Mobile handling)
            const isMobile = window.innerWidth < 1024;

            if (isMobile) {
                // Mobile: Translate X logic
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full'); // Open
                    overlay.classList.remove('hidden');
                    body.style.overflow = 'hidden'; // Prevent scroll
                } else {
                    sidebar.classList.add('-translate-x-full'); // Close
                    overlay.classList.add('hidden');
                    body.style.overflow = 'auto';
                }
            } else {
                // Desktop: Collapse logic
                if (sidebar.classList.contains('lg:translate-x-0')) {
                    // Close Sidebar on Desktop
                    sidebar.classList.replace('lg:translate-x-0', 'lg:-translate-x-full');
                    mainContent.classList.replace('lg:ml-64', 'lg:ml-0');
                    desktopHamburger.classList.remove('hidden');
                } else {
                    // Open Sidebar on Desktop
                    sidebar.classList.replace('lg:-translate-x-full', 'lg:translate-x-0');
                    mainContent.classList.replace('lg:ml-0', 'lg:ml-64');
                    desktopHamburger.classList.add('hidden');
                }
            }
        }

        // Reset on Resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebarOverlay').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>