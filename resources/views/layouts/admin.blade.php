<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - LPS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F0F2F5;
            overflow-x: hidden;
        }

        /* Update ke warna Orange */
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 4px solid #ffffff;
        }

        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        /* Rotasi icon arrow saat dropdown terbuka */
        .dropdown-active i.fa-chevron-right {
            transform: rotate(90deg);
            transition: transform 0.2s;
        }

        .dropdown-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-container.open {
            max-height: 250px;
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen bg-[#F0F2F5]">

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <aside id="sidebar"
        class="sidebar-transition w-64 bg-orange-600 text-white flex flex-col fixed h-full z-50 -translate-x-full lg:translate-x-0 shadow-xl">
        <div class="p-6 flex items-center justify-between border-b border-white/20">
            <div id="sidebarLogo" class="flex items-center space-x-3 opacity-100 transition-opacity duration-300">
                <div class="bg-white p-2 rounded-lg text-orange-600 shadow-sm">
                    <i class="fas fa-shield-halved text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold leading-none tracking-tight text-lg">LPS</h1>
                    <p class="text-[10px] text-orange-100 uppercase">Lembaga Penjamin Simpanan</p>
                </div>
            </div>
            <button onclick="toggleSidebar()" class="text-orange-100 hover:text-white focus:outline-none">
                <i class="fas fa-bars text-xl hidden lg:block"></i>
                <i class="fas fa-times text-xl lg:hidden"></i>
            </button>
        </div>

        <nav class="flex-grow py-4 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'sidebar-active text-white font-semibold' : 'text-orange-50' }} flex items-center px-6 py-3 text-sm hover:bg-white/10 transition">
                <i class="fas fa-th-large w-6"></i> <span>Dashboard</span>
            </a>

            <div class="mt-2">
                <button onclick="toggleDropdown('masterDropdown')" id="btn-masterDropdown"
                    class="w-full flex items-center justify-between px-6 py-3 text-sm text-orange-50 hover:bg-white/10 transition group">
                    <div class="flex items-center">
                        <i class="fas fa-database w-6"></i>
                        <span class="font-semibold uppercase text-xs tracking-wider">Master Data</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] transition-transform duration-200"></i>
                </button>

                <div id="masterDropdown"
                    class="dropdown-container bg-orange-700/40 {{ request()->routeIs('admin.gallery.*') || request()->routeIs('admin.scanner') ? 'open' : '' }}">

                    {{-- Menu Gallery --}}
                    <a href="{{ route('admin.gallery.index') }}"
                        class="{{ request()->routeIs('admin.gallery.*') ? 'sidebar-active text-white' : 'text-orange-100' }} flex items-center pl-14 pr-6 py-2 text-sm hover:bg-white/10 transition">
                        <i class="fas fa-images w-5 text-xs"></i> <span>Gallery Image</span>
                    </a>

                    {{-- Menu Scanner --}}
                    <a href="{{ route('admin.scanner') }}"
                        class="{{ request()->routeIs('admin.scanner') ? 'sidebar-active text-white' : 'text-orange-100' }} flex items-center pl-14 pr-6 py-2 text-sm hover:bg-white/10 transition">
                        <i class="fas fa-qrcode w-5 text-xs"></i> <span>Scan QR Code</span>
                    </a>

                    {{-- Menu Photobooth --}}
                    <a href="{{ route('admin.photobooth') }}"
                        class="{{ request()->routeIs('admin.photobooth') ? 'sidebar-active text-white' : 'text-orange-100' }} flex items-center pl-14 pr-6 py-2 text-sm hover:bg-white/10 transition">
                        <i class="fas fa-camera-retro w-5 text-xs"></i> <span>Photobooth AI</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/20 flex items-center justify-between bg-orange-800/50">
            <div class="flex items-center space-x-3 overflow-hidden">
                <div
                    class="w-8 h-8 flex-shrink-0 rounded-full bg-white flex items-center justify-center text-xs font-bold uppercase text-orange-600">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="text-[10px] truncate text-orange-50">{{ auth()->user()->email ?? 'admin@lps.go.id' }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-orange-200 hover:text-white transition">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </aside>

    <div id="mainContent" class="sidebar-transition flex-grow flex flex-col min-h-screen lg:ml-64">

        <header id="mobileHeader" class="bg-white shadow-sm border-b p-4 flex items-center lg:hidden shrink-0">
            <button onclick="toggleSidebar()" class="text-orange-600 p-2 focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 class="ml-4 font-bold text-gray-800">LPS</h2>
        </header>

        <header id="desktopHeader"
            class="bg-white shadow-sm border-b px-8 py-4 hidden lg:flex items-center sticky top-0 z-30 sidebar-transition shrink-0">
            <button id="desktopHamburger" onclick="toggleSidebar()"
                class="text-orange-600 p-2 focus:outline-none mr-4 hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h2 class="font-bold text-[#112D4E]">Lembaga Penjamin Simpanan</h2>
        </header>

        <main class="p-4 md:p-6 lg:p-8 flex-grow">
            @yield('content')
        </main>

        <footer class="p-6 text-center text-[10px] text-gray-400 uppercase tracking-widest shrink-0">
            © 2026 LPS - Lembaga Penjamin Simpanan
        </footer>
    </div>

    <script>
        // Fungsi Dropdown Master Data
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const btn = document.getElementById('btn-' + id);

            dropdown.classList.toggle('open');
            btn.classList.toggle('dropdown-active');

            // Logika arrow rotation
            const icon = btn.querySelector('.fa-chevron-right');
            if (dropdown.classList.contains('open')) {
                icon.style.transform = 'rotate(90deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');
            const desktopHamburger = document.getElementById('desktopHamburger');
            const isDesktop = window.innerWidth >= 1024;

            if (isDesktop) {
                if (sidebar.classList.contains('lg:translate-x-0')) {
                    sidebar.classList.replace('lg:translate-x-0', 'lg:-translate-x-full');
                    mainContent.classList.replace('lg:ml-64', 'lg:ml-0');
                    desktopHamburger.classList.remove('hidden');
                } else {
                    sidebar.classList.replace('lg:-translate-x-full', 'lg:translate-x-0');
                    mainContent.classList.replace('lg:ml-0', 'lg:ml-64');
                    desktopHamburger.classList.add('hidden');
                }
            } else {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                }
            }
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebarOverlay').classList.add('hidden');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
