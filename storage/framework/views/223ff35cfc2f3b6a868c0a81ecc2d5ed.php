<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Admin Panel</title>

    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
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
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="min-h-screen text-slate-600">

    
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm" onclick="toggleSidebar()"></div>

    
    <aside id="sidebar"
        class="sidebar-transition w-64 bg-white border-r border-slate-200 fixed h-full z-50 -translate-x-full lg:translate-x-0 flex flex-col shadow-sm">
        
        
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
            
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        
        <nav class="flex-grow py-6 overflow-y-auto px-4 space-y-1">
            
            
            <div class="px-4 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Main Menu</div>

            
            <a href="<?php echo e(route('admin.dashboard')); ?>"
                class="<?php echo e(request()->routeIs('admin.dashboard') || request()->routeIs('admin.analytics') ? 'sidebar-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'); ?> flex items-center px-4 py-2.5 text-sm rounded-lg transition-all group">
                <i class="<?php echo e(request()->routeIs('admin.dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'); ?> fas fa-home w-5 transition-colors mr-3"></i>
                <span>Dashboard</span>
            </a>

            
            <div class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aplikasi</div>

            <div>
                <button onclick="toggleDropdown('masterDropdownDataset')" id="btn-masterDropdownDataset"
                    class="<?php echo e(request()->is('admin/gallery*') || request()->is('admin/photobooth-dataset') ? 'text-slate-800 font-medium bg-slate-50' : 'text-slate-500'); ?> w-full flex items-center justify-between px-4 py-2.5 text-sm hover:bg-slate-50 rounded-lg transition group">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group w-5 mr-3 text-slate-400 group-hover:text-slate-600"></i>
                        <span>Dataset</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 transition-transform duration-200"></i>
                </button>

                
                <div id="masterDropdownDataset"
                    class="dropdown-container pl-4 space-y-1 mt-1 <?php echo e(request()->routeIs('admin.gallery.*') || request()->routeIs('admin.photobooth') ? 'open' : ''); ?>">

                    <a href="<?php echo e(route('admin.gallery.index')); ?>"
                        class="<?php echo e(request()->routeIs('admin.gallery.*') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800'); ?> block px-4 py-2 text-sm rounded-lg transition border-l-2 <?php echo e(request()->routeIs('admin.gallery.*') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300'); ?>">
                        <span>Dataset AR</span>
                    </a>

                    <a href="<?php echo e(route('admin.photobooth.index')); ?>"
                        class="<?php echo e(request()->routeIs('admin.photobooth.index') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800'); ?> block px-4 py-2 text-sm rounded-lg transition border-l-2 <?php echo e(request()->routeIs('admin.photobooth.index*') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300'); ?>">
                        <span>Dataset Photobooth</span>
                    </a>

                </div>
            </div>
            
            <div>
                <button onclick="toggleDropdown('masterDropdown')" id="btn-masterDropdown"
                    class="<?php echo e(request()->is('admin/tools*') || request()->is('admin/gallery*') || request()->is('admin/scanner*') ? 'text-slate-800 font-medium bg-slate-50' : 'text-slate-500'); ?> w-full flex items-center justify-between px-4 py-2.5 text-sm hover:bg-slate-50 rounded-lg transition group">
                    <div class="flex items-center">
                        <i class="fas fa-layer-group w-5 mr-3 text-slate-400 group-hover:text-slate-600"></i>
                        <span>Apps & Tools</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-300 transition-transform duration-200"></i>
                </button>

                
                <div id="masterDropdown"
                    class="dropdown-container pl-4 space-y-1 mt-1 <?php echo e(request()->routeIs('admin.scanner') || request()->routeIs('admin.photobooth') || request()->routeIs('tools.*') ? 'open' : ''); ?>">

                    <a href="<?php echo e(route('admin.scanner')); ?>"
                        class="<?php echo e(request()->routeIs('admin.scanner') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800'); ?> block px-4 py-2 text-sm rounded-lg transition border-l-2 <?php echo e(request()->routeIs('admin.scanner') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300'); ?>">
                        <span>Scan Object AR</span>
                    </a>

                    <a href="<?php echo e(route('admin.points.scan')); ?>"
                        class="<?php echo e(request()->routeIs('admin.points.scan') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800'); ?> block px-4 py-2 text-sm rounded-lg transition border-l-2 <?php echo e(request()->routeIs('admin.points.scan') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300'); ?>">
                        <span>Scan QR Code</span>
                    </a>

                    
                    <div class="border-t border-slate-100 my-2 mx-4"></div>

                    <a href="<?php echo e(route('tools.quiz_manager')); ?>"
                        class="<?php echo e(request()->routeIs('tools.quiz_manager') ? 'text-emerald-600 font-medium bg-emerald-50' : 'text-slate-500 hover:text-slate-800'); ?> block px-4 py-2 text-sm rounded-lg transition border-l-2 <?php echo e(request()->routeIs('tools.quiz_manager') ? 'border-emerald-500' : 'border-transparent hover:border-slate-300'); ?>">
                        <span>Quiz Manager</span>
                    </a>
                </div>
            </div>
        </nav>

        
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    
                    <div class="w-9 h-9 flex-shrink-0 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-sm font-bold text-emerald-700">
                        <?php echo e(substr(auth()->user()->name ?? 'A', 0, 1)); ?>

                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold text-slate-700 truncate leading-none mb-0.5"><?php echo e(auth()->user()->name ?? 'Admin'); ?></p>
                        <p class="text-[10px] text-slate-400 truncate">Administrator</p>
                    </div>
                </div>
                
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    
    <div id="mainContent" class="sidebar-transition flex-col min-h-screen lg:ml-64 flex">

        
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
                <?php echo e(number_format($totalPoints ?? 0)); ?>

            </div>
        </header>

        
        <header id="desktopHeader"
            class="bg-white shadow-sm border-b px-8 py-4 hidden lg:flex items-center justify-between sticky top-0 z-30 sidebar-transition shrink-0">

            
            <div class="flex items-center">
                <button id="desktopHamburger" onclick="toggleSidebar()"
                    class="text-orange-600 p-2 focus:outline-none mr-4 hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="font-bold text-[#112D4E]">Lembaga Penjamin Simpanan</h2>
            </div>

            
            <div class="flex items-center gap-4">

                
                <div
                    class="flex items-center gap-2 bg-orange-50 text-orange-600 px-4 py-2 rounded-full text-sm font-semibold shadow-sm">
                    <i class="fas fa-coins"></i>
                    <?php echo e(number_format($totalPoints ?? 0)); ?> Poin
                </div>

                
                <div class="flex items-center gap-3">
                    <div class="text-right leading-tight">
                        <p class="text-sm font-semibold text-gray-800">
                            <?php echo e(auth()->user()->name); ?>

                        </p>
                        <p class="text-[11px] text-gray-400">
                            <?php echo e(auth()->user()->email); ?>

                        </p>
                    </div>

                    
                    <div
                        class="w-9 h-9 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold uppercase">
                        <?php echo e(substr(auth()->user()->name ?? 'U', 0, 1)); ?>

                    </div>
                </div>
            </div>
        </header>

        
        <main class="p-4 md:p-6 lg:p-8 flex-grow bg-[#f8fafc]">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <footer class="p-6 text-center text-[10px] text-slate-400 uppercase tracking-widest shrink-0 bg-[#f8fafc]">
            © 2026 LPS - Lembaga Penjamin Simpanan
        </footer>
    </div>

    
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /var/www/html/resources/views/layouts/admin.blade.php ENDPATH**/ ?>