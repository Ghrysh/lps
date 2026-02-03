<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pameran Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f4f8;
        }

        [x-cloak] {
            display: none !important;
        }

        .card-blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .card-red {
            background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
        }

        .card-amber {
            background: linear-gradient(135deg, #fff8e1 0%, #ffe082 100%);
        }

        .card-teal {
            background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
        }

        .card-green {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        }

        .card-rose {
            background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
        }

        .tab-active {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .tab-inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        ::-webkit-scrollbar {
            height: 4px;
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        /* ── Mobile card rows (tables → cards < md) ── */
        .card-row {
            border-top: 1px solid #f1f5f9;
        }

        .card-row:first-child {
            border-top: none;
        }

        /* hide table, show cards on mobile; reverse on md+ */
        .tbl-desktop {
            display: none;
        }

        .tbl-mobile {
            display: block;
        }

        @media (min-width: 768px) {
            .tbl-desktop {
                display: block;
            }

            .tbl-mobile {
                display: none;
            }
        }
    </style>
</head>

<body class="text-slate-600 min-h-screen">

    <!-- ═══════════════════ TOP NAV ═══════════════════ -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 sm:h-16 items-center">
                <!-- Logo -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="bg-emerald-600 text-white p-2 sm:p-2.5 rounded-lg sm:rounded-xl">
                        <i class="fas fa-chart-line text-base sm:text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-sm sm:text-lg font-bold text-slate-800 leading-tight">Admin Dashboard</h1>
                        <p class="text-xs text-slate-400 hidden sm:block">Pameran Indonesia</p>
                    </div>
                </div>
                <!-- Nav actions -->
                <div class="flex items-center gap-1.5 sm:gap-3">
                    <!-- mobile: icon only; sm+: icon + label -->
                    <button
                        class="flex items-center justify-center gap-2 w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                        <i class="fas fa-sync-alt text-sm"></i>
                        <span class="hidden sm:inline">Refresh</span>
                    </button>
                    <button
                        class="flex items-center justify-center gap-2 w-9 h-9 sm:w-auto sm:h-auto sm:px-4 sm:py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════ MAIN ═══════════════════ -->
    <main class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-5 sm:py-8" x-data="{ activeTab: 'pengunjung' }">

        <!-- ── HEADER ── -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 sm:mb-6 gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800">Dashboard Analitik</h2>
                <p class="text-slate-400 text-xs sm:text-sm">Monitor aktivitas pengunjung pameran secara real-time</p>
            </div>
            <!-- Controls -->
            <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center w-full md:w-auto">
                <!-- Row 1: pills + Pilih Tanggal -->
                <div class="flex gap-2 items-center flex-wrap">
                    <div class="flex bg-white rounded-lg border border-slate-200 p-1 shadow-sm">
                        <button
                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-medium text-slate-500 rounded-md hover:bg-slate-100 transition">Hari
                            Ini</button>
                        <button
                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-medium text-slate-500 rounded-md hover:bg-slate-100 transition">7
                            Hari</button>
                        <button
                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-medium text-slate-500 rounded-md hover:bg-slate-100 transition">30
                            Hari</button>
                        <button
                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs font-bold bg-emerald-600 text-white rounded-md shadow-sm">Semua</button>
                    </div>
                    <button
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 shadow-sm transition">
                        <i class="fas fa-calendar-alt text-slate-400"></i> Pilih Tanggal
                    </button>
                </div>
                <!-- Row 2 (mobile stacks; md+ inline) -->
                <button
                    class="flex items-center gap-1.5 px-4 py-1.5 text-xs sm:text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition w-full sm:w-auto justify-center sm:justify-start">
                    <i class="fas fa-file-alt"></i> Export Semua
                </button>
            </div>
        </div>

        <!-- ── STAT CARDS ── -->
        <!-- mobile 2-col  |  tablet 3-col  |  desktop 6-col -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-4 mb-5 sm:mb-6">
            <div class="card-blue p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
                <div class="text-blue-500 text-lg sm:text-2xl mb-1 sm:mb-2"><i class="fas fa-users"></i></div>
                <h3 class="text-xl sm:text-3xl font-bold text-slate-800">130</h3>
                <p class="text-xs text-slate-500 mt-0.5">Total Pengunjung</p>
            </div>
            <div class="card-red p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
                <div class="text-red-400 text-lg sm:text-2xl mb-1 sm:mb-2"><i class="fas fa-qrcode"></i></div>
                <h3 class="text-xl sm:text-3xl font-bold text-slate-800">108</h3>
                <p class="text-xs text-slate-500 mt-0.5">Total Scan Panel</p>
            </div>
            <div class="card-amber p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
                <div class="text-amber-500 text-lg sm:text-2xl mb-1 sm:mb-2"><i class="fas fa-chart-line"></i></div>
                <h3 class="text-xl sm:text-3xl font-bold text-slate-800">10367</h3>
                <p class="text-xs text-slate-500 mt-0.5">Total Poin Terkumpul</p>
            </div>
            <div class="card-teal p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
                <div class="text-teal-500 text-lg sm:text-2xl mb-1 sm:mb-2"><i class="fas fa-camera"></i></div>
                <h3 class="text-xl sm:text-3xl font-bold text-slate-800">60</h3>
                <p class="text-xs text-slate-500 mt-0.5">Foto Booth</p>
            </div>
            <div class="card-green p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
                <div class="text-green-500 text-lg sm:text-2xl mb-1 sm:mb-2"><i class="fas fa-gamepad"></i></div>
                <h3 class="text-xl sm:text-3xl font-bold text-slate-800">80</h3>
                <p class="text-xs text-slate-500 mt-0.5">Game Dimainkan</p>
            </div>
            <div class="card-rose p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
                <div class="text-rose-400 text-lg sm:text-2xl mb-1 sm:mb-2"><i class="fas fa-coffee"></i></div>
                <h3 class="text-xl sm:text-3xl font-bold text-slate-800">12/40</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kode Kopi <span class="text-slate-400">ditukar</span></p>
            </div>
        </div>

        <!-- ── CHARTS ── -->
        <!--
         mobile  : Tren full-width, then Gender + Aktivitas 2-col below
         md+     : 3 columns side-by-side  (Tren | Gender | Aktivitas)
    -->

        <div class="md:hidden mb-3">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
                    <i class="fas fa-chart-line text-emerald-500"></i> Tren Pengunjung
                </h3>
                <div id="chart-trend-mobile"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">

            <div
                class="hidden md:block lg:col-span-2 bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 h-full">
                <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm sm:text-base">
                    <i class="fas fa-chart-line text-emerald-500"></i> Tren Pengunjung
                </h3>
                <div id="chart-trend-desktop"></div>
            </div>

            <div
                class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 flex flex-col h-full">
                <h3
                    class="font-bold text-slate-800 mb-2 sm:mb-3 flex items-center gap-1.5 sm:gap-2 text-xs sm:text-base">
                    <i class="fas fa-venus-mars text-amber-500"></i> Gender
                </h3>

                <div id="chart-gender" class="flex-1 flex items-center justify-center w-full"></div>

                <div class="flex justify-between text-xs text-slate-400 mt-1.5 sm:mt-2 px-0.5 sm:px-1">
                    <span>Total: <strong class="text-slate-600">130</strong></span>
                    <span>Avg Poin: <strong class="text-amber-500">80</strong></span>
                </div>
            </div>

            <div
                class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 flex flex-col h-full">
                <h3
                    class="font-bold text-slate-800 mb-2 sm:mb-3 flex items-center gap-1.5 sm:gap-2 text-xs sm:text-base">
                    <i class="fas fa-bolt text-rose-500"></i> Aktivitas
                </h3>

                <div id="chart-activity" class="flex-1 flex items-center justify-center w-full"></div>
            </div>
        </div>

        <!-- ── LAPORAN DETAIL ── -->
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Header + Tabs -->
            <div class="px-3 sm:px-6 pt-4 sm:pt-6 pb-0">
                <h3 class="font-bold text-sm sm:text-lg text-slate-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-emerald-600"></i> Laporan Detail
                </h3>
                <!-- mobile: horizontal scroll pills | md+: equal-grid -->
                <div
                    class="flex md:grid md:grid-cols-4 gap-1.5 md:gap-0 bg-slate-100 rounded-lg md:rounded-xl p-1 overflow-x-auto md:overflow-visible">
                    <button @click="activeTab = 'pengunjung'"
                        :class="activeTab === 'pengunjung' ? 'tab-active text-slate-800' : 'tab-inactive'"
                        class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 md:px-0 py-2 md:py-2.5 rounded-md md:rounded-lg font-medium text-xs sm:text-sm transition whitespace-nowrap flex-shrink-0 md:flex-shrink">
                        <i class="fas fa-users"></i> Pengunjung
                    </button>
                    <button @click="activeTab = 'foto'"
                        :class="activeTab === 'foto' ? 'tab-active text-slate-800' : 'tab-inactive'"
                        class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 md:px-0 py-2 md:py-2.5 rounded-md md:rounded-lg font-medium text-xs sm:text-sm transition whitespace-nowrap flex-shrink-0 md:flex-shrink">
                        <i class="fas fa-camera"></i> Foto Booth
                    </button>
                    <button @click="activeTab = 'game'"
                        :class="activeTab === 'game' ? 'tab-active text-slate-800' : 'tab-inactive'"
                        class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 md:px-0 py-2 md:py-2.5 rounded-md md:rounded-lg font-medium text-xs sm:text-sm transition whitespace-nowrap flex-shrink-0 md:flex-shrink">
                        <i class="fas fa-gamepad"></i> Game
                    </button>
                    <button @click="activeTab = 'kopi'"
                        :class="activeTab === 'kopi' ? 'tab-active text-slate-800' : 'tab-inactive'"
                        class="flex items-center justify-center gap-1.5 sm:gap-2 px-3 md:px-0 py-2 md:py-2.5 rounded-md md:rounded-lg font-medium text-xs sm:text-sm transition whitespace-nowrap flex-shrink-0 md:flex-shrink">
                        <i class="fas fa-coffee"></i> Kopi
                    </button>
                </div>
            </div>

            <!-- ════════════ TAB CONTENTS ════════════ -->
            <div class="p-3 sm:p-6">

                <!-- ═══ PENGUNJUNG ═══ -->
                <div x-show="activeTab === 'pengunjung'" x-cloak>
                    <!-- badge bar -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 mb-4 sm:mb-5">
                        <div class="flex gap-2 flex-wrap">
                            <span
                                class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                <i class="fas fa-users text-slate-400"></i> Total: 130
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                <i class="fas fa-male"></i> L: 83
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-500 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                <i class="fas fa-female"></i> P: 47
                            </span>
                        </div>
                        <button
                            class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-xs sm:text-sm border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition self-start sm:self-auto">
                            <i class="fas fa-download"></i> Export Excel
                        </button>
                    </div>

                    <!-- MOBILE CARDS -->
                    <div class="tbl-mobile" id="pengunjung-cards"></div>

                    <!-- DESKTOP TABLE -->
                    <div class="tbl-desktop overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-slate-400 text-xs font-semibold border-b border-slate-100">
                                    <th class="pb-3 px-2">#</th>
                                    <th class="pb-3 px-2">Session ID</th>
                                    <th class="pb-3 px-2">Gender</th>
                                    <th class="pb-3 px-2">Poin</th>
                                    <th class="pb-3 px-2">Waktu Daftar</th>
                                    <th class="pb-3 px-2">Terakhir Aktif</th>
                                </tr>
                            </thead>
                            <tbody id="pengunjung-tbody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ FOTO BOOTH ═══ -->
                <div x-show="activeTab === 'foto'" x-cloak>
                    <div class="flex justify-end mb-4 sm:mb-5">
                        <button
                            class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-xs sm:text-sm border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition">
                            <i class="fas fa-download"></i> Export Excel
                        </button>
                    </div>
                    <!-- Distribusi -->
                    <div class="bg-slate-50 border border-slate-100 rounded-lg sm:rounded-xl p-3 sm:p-5 mb-4 sm:mb-6">
                        <h4 class="font-bold text-slate-700 mb-3 text-xs sm:text-sm">Distribusi Kostum Daerah</h4>
                        <div class="space-y-3 sm:space-y-3.5" id="kostum-bars"></div>
                    </div>
                    <!-- Riwayat -->
                    <div class="bg-slate-50 border border-slate-100 rounded-lg sm:rounded-xl p-3 sm:p-5">
                        <h4 class="font-bold text-slate-700 mb-3 text-xs sm:text-sm">Riwayat Foto Booth Terbaru</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead>
                                    <tr class="text-slate-400 text-xs font-semibold border-b border-slate-200">
                                        <th class="pb-2 px-1.5 sm:px-2">#</th>
                                        <th class="pb-2 px-1.5 sm:px-2">Kostum Daerah</th>
                                        <th class="pb-2 px-1.5 sm:px-2">Poin</th>
                                        <th class="pb-2 px-1.5 sm:px-2">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-slate-200">
                                        <td class="py-3 px-1.5 sm:px-2 text-slate-600">1</td>
                                        <td class="py-3 px-1.5 sm:px-2">
                                            <span
                                                class="inline-flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-600 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Papua
                                            </span>
                                        </td>
                                        <td class="py-3 px-1.5 sm:px-2 text-slate-600">5</td>
                                        <td class="py-3 px-1.5 sm:px-2 text-slate-400 text-xs whitespace-nowrap">29 Jan
                                            2026, 00:21</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ═══ GAME ═══ -->
                <div x-show="activeTab === 'game'" x-cloak>
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 mb-4 sm:mb-5">
                        <div class="flex gap-2 flex-wrap">
                            <span
                                class="inline-flex items-center border border-slate-200 bg-slate-50 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-semibold">Total:
                                80</span>
                            <span
                                class="inline-flex items-center bg-green-50 border border-green-200 text-green-600 px-2.5 py-1 rounded-lg text-xs font-semibold">Avg:
                                287</span>
                            <span
                                class="inline-flex items-center bg-green-50 border border-green-200 text-green-600 px-2.5 py-1 rounded-lg text-xs font-semibold">Poin:
                                1159</span>
                        </div>
                        <button
                            class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-xs sm:text-sm border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition self-start sm:self-auto">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>

                    <!-- MOBILE CARDS -->
                    <div class="tbl-mobile" id="game-cards"></div>

                    <!-- DESKTOP TABLE -->
                    <div class="tbl-desktop overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-slate-400 text-xs font-semibold border-b border-slate-100">
                                    <th class="pb-3 px-2">Rank</th>
                                    <th class="pb-3 px-2">Visitor</th>
                                    <th class="pb-3 px-2">Skor</th>
                                    <th class="pb-3 px-2">Poin</th>
                                    <th class="pb-3 px-2">Waktu</th>
                                </tr>
                            </thead>
                            <tbody id="game-tbody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- ═══ KOPI ═══ -->
                <div x-show="activeTab === 'kopi'" x-cloak>
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0 mb-4 sm:mb-5">
                        <div class="flex gap-2 flex-wrap">
                            <span
                                class="inline-flex items-center border border-slate-200 bg-slate-50 text-slate-700 px-2.5 py-1 rounded-lg text-xs font-semibold">Total:
                                40</span>
                            <span
                                class="inline-flex items-center gap-1.5 bg-green-50 border border-green-200 text-green-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                <i class="fas fa-check-circle"></i> 12
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                <i class="fas fa-circle"></i> 0
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-600 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                <i class="fas fa-clock"></i> 28
                            </span>
                        </div>
                        <button
                            class="flex items-center gap-2 text-slate-500 hover:text-slate-700 text-xs sm:text-sm border border-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-50 transition self-start sm:self-auto">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>

                    <!-- MOBILE CARDS -->
                    <div class="tbl-mobile" id="kopi-cards"></div>

                    <!-- DESKTOP TABLE -->
                    <div class="tbl-desktop overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="text-slate-400 text-xs font-semibold border-b border-slate-100">
                                    <th class="pb-3 px-2">#</th>
                                    <th class="pb-3 px-2">Kode</th>
                                    <th class="pb-3 px-2">Status</th>
                                    <th class="pb-3 px-2">Poin</th>
                                    <th class="pb-3 px-2">Dibuat</th>
                                    <th class="pb-3 px-2">Kadaluarsa</th>
                                </tr>
                            </thead>
                            <tbody id="kopi-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end tab contents -->
        </div><!-- end laporan detail -->
    </main>

    <!-- ═══════════════ SCRIPTS ═══════════════ -->
    <script>
        // ══════ DATA ══════
        const pengunjungData = [{
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '03 Feb, 12:23',
                aktif: '03 Feb, 12:23'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '03 Feb, 10:04',
                aktif: '03 Feb, 10:04'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '30 Jan, 09:35',
                aktif: '30 Jan, 09:35'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '30 Jan, 03:38',
                aktif: '30 Jan, 03:38'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '29 Jan, 23:19',
                aktif: '29 Jan, 23:19'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '29 Jan, 21:35',
                aktif: '29 Jan, 21:35'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 0,
                daftar: '29 Jan, 14:57',
                aktif: '29 Jan, 14:57'
            },
            {
                id: 'visitor_....',
                gender: 'P',
                poin: 45,
                daftar: '29 Jan, 12:00',
                aktif: '29 Jan, 14:30'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 120,
                daftar: '28 Jan, 18:15',
                aktif: '28 Jan, 20:45'
            },
            {
                id: 'visitor_....',
                gender: 'P',
                poin: 85,
                daftar: '28 Jan, 16:00',
                aktif: '28 Jan, 18:22'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 60,
                daftar: '28 Jan, 10:30',
                aktif: '28 Jan, 12:10'
            },
            {
                id: 'visitor_....',
                gender: 'P',
                poin: 30,
                daftar: '27 Jan, 22:00',
                aktif: '27 Jan, 23:15'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 95,
                daftar: '27 Jan, 19:45',
                aktif: '27 Jan, 21:30'
            },
            {
                id: 'visitor_....',
                gender: 'L',
                poin: 150,
                daftar: '27 Jan, 14:00',
                aktif: '27 Jan, 16:45'
            },
            {
                id: 'visitor_....',
                gender: 'P',
                poin: 70,
                daftar: '26 Jan, 11:20',
                aktif: '26 Jan, 13:50'
            },
        ];
        const kostumData = [{
                nama: 'Sulawesi',
                count: 12,
                persen: 20.0,
                color: '#a855f7'
            },
            {
                nama: 'Sumatera',
                count: 10,
                persen: 16.7,
                color: '#3b82f6'
            },
            {
                nama: 'Kalimantan',
                count: 10,
                persen: 16.7,
                color: '#f97316'
            },
            {
                nama: 'Bali',
                count: 10,
                persen: 16.7,
                color: '#22c55e'
            },
            {
                nama: 'Papua',
                count: 9,
                persen: 15.0,
                color: '#ef4444'
            },
            {
                nama: 'Jawa',
                count: 9,
                persen: 15.0,
                color: '#f59e0b'
            },
        ];
        const gameData = [{
                rank: 1,
                visitor: '2e75f116...',
                skor: 549,
                poin: 18,
                waktu: '24 Jan, 04:51'
            },
            {
                rank: 2,
                visitor: '8f637d7f...',
                skor: 531,
                poin: 15,
                waktu: '18 Jan, 05:11'
            },
            {
                rank: 3,
                visitor: '80792f48...',
                skor: 509,
                poin: 22,
                waktu: '16 Jan, 17:39'
            },
            {
                rank: 4,
                visitor: 'b2bc5f49...',
                skor: 503,
                poin: 22,
                waktu: '28 Jan, 18:46'
            },
            {
                rank: 5,
                visitor: 'ac641f3e...',
                skor: 500,
                poin: 14,
                waktu: '23 Jan, 02:13'
            },
            {
                rank: 6,
                visitor: '9156c01f...',
                skor: 495,
                poin: 11,
                waktu: '21 Jan, 22:53'
            },
            {
                rank: 7,
                visitor: '694b2617...',
                skor: 493,
                poin: 13,
                waktu: '15 Jan, 14:03'
            },
            {
                rank: 8,
                visitor: 'e886567c...',
                skor: 492,
                poin: 18,
                waktu: '26 Jan, 15:22'
            },
            {
                rank: 9,
                visitor: 'a3c8f291...',
                skor: 488,
                poin: 16,
                waktu: '22 Jan, 09:40'
            },
            {
                rank: 10,
                visitor: 'd7e14b82...',
                skor: 475,
                poin: 12,
                waktu: '19 Jan, 11:05'
            },
        ];
        const kopiData = [{
                kode: 'KOPI-DB0033',
                status: 'Expired',
                poin: 20,
                dibuat: '28 Jan, 15:04',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-960167',
                status: 'Expired',
                poin: 20,
                dibuat: '28 Jan, 13:43',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-FE74DC',
                status: 'Expired',
                poin: 20,
                dibuat: '28 Jan, 07:52',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-61CD87',
                status: 'Expired',
                poin: 20,
                dibuat: '28 Jan, 03:39',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-04F001',
                status: 'Expired',
                poin: 20,
                dibuat: '27 Jan, 23:31',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-149CD7',
                status: 'Expired',
                poin: 20,
                dibuat: '27 Jan, 21:07',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-A8019A',
                status: 'Ditukar',
                poin: 20,
                dibuat: '25 Jan, 22:22',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-ADB46A',
                status: 'Expired',
                poin: 20,
                dibuat: '25 Jan, 13:30',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-7F2C91',
                status: 'Ditukar',
                poin: 20,
                dibuat: '25 Jan, 10:15',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-B3E044',
                status: 'Expired',
                poin: 20,
                dibuat: '24 Jan, 19:48',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-C52A78',
                status: 'Ditukar',
                poin: 20,
                dibuat: '24 Jan, 08:30',
                exp: '30 Jan, 05:07'
            },
            {
                kode: 'KOPI-D91F56',
                status: 'Expired',
                poin: 20,
                dibuat: '23 Jan, 22:10',
                exp: '30 Jan, 05:07'
            },
        ];

        // ══════ HELPERS ══════
        const genderBadge = (g) => {
            const isL = g === 'L';
            return `<span class="inline-flex items-center justify-center w-7 h-7 rounded-full ${isL?'bg-blue-100 text-blue-600':'bg-red-100 text-red-500'} font-bold text-xs">${g}</span>`;
        };
        const poinBadge = (n) =>
            `<span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-orange-100 text-orange-500 font-bold text-xs">${n}</span>`;
        const trophyColors = {
            1: '#eab308',
            2: '#9ca3af',
            3: '#b45309'
        };
        const rankHtml = (r) => r <= 3 ?
            `<span style="color:${trophyColors[r]}"><i class="fas fa-trophy"></i></span><span class="text-slate-500 font-semibold ml-1">${r}</span>` :
            `<span class="text-slate-500 font-semibold">${r}</span>`;
        const statusBadge = (s) => s === 'Ditukar' ?
            `<span class="inline-flex items-center gap-1.5 bg-green-50 border border-green-200 text-green-600 px-2.5 py-1 rounded-full text-xs font-bold"><i class="fas fa-check-circle"></i> Ditukar</span>` :
            `<span class="inline-flex items-center gap-1.5 bg-red-500 text-white px-2.5 py-1 rounded-full text-xs font-bold"><i class="fas fa-clock"></i> Expired</span>`;

        // ══════ PENGUNJUNG ══════
        // — desktop table —
        (function() {
            const tbody = document.getElementById('pengunjung-tbody');
            pengunjungData.forEach((p, i) => {
                tbody.innerHTML += `
        <tr class="border-t border-slate-50 hover:bg-slate-50 transition">
            <td class="py-3.5 px-2 text-slate-500 font-medium">${i+1}</td>
            <td class="py-3.5 px-2 font-mono text-slate-500 text-xs">${p.id}</td>
            <td class="py-3.5 px-2">${genderBadge(p.gender)}</td>
            <td class="py-3.5 px-2">${poinBadge(p.poin)}</td>
            <td class="py-3.5 px-2 text-slate-400 text-xs">${p.daftar}</td>
            <td class="py-3.5 px-2 text-slate-400 text-xs">${p.aktif}</td>
        </tr>`;
            });
        })();
        // — mobile cards —
        (function() {
            const el = document.getElementById('pengunjung-cards');
            pengunjungData.forEach((p, i) => {
                el.innerHTML += `
        <div class="card-row py-3 flex items-start justify-between">
            <div class="flex items-center gap-2.5">
                <span class="text-slate-400 font-semibold text-xs w-4 text-center">${i+1}</span>
                <div>
                    <p class="font-mono text-slate-600 text-xs font-semibold">${p.id}</p>
                    <p class="text-slate-400 text-xs mt-0.5">${p.daftar}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                ${genderBadge(p.gender)}
                ${poinBadge(p.poin)}
            </div>
        </div>`;
            });
        })();

        // ══════ KOSTUM BARS ══════
        (function() {
            const el = document.getElementById('kostum-bars');
            kostumData.forEach(k => {
                el.innerHTML += `
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <span class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:${k.color}"></span>${k.nama}
                </span>
                <span class="text-xs text-slate-400">${k.count} (${k.persen.toFixed(1)}%)</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="h-2.5 rounded-full transition-all duration-700" style="width:${k.persen}%;background:${k.color}"></div>
            </div>
        </div>`;
            });
        })();

        // ══════ GAME ══════
        // — desktop table —
        (function() {
            const tbody = document.getElementById('game-tbody');
            gameData.forEach(g => {
                tbody.innerHTML += `
        <tr class="border-t border-slate-50 hover:bg-slate-50 transition">
            <td class="py-3.5 px-2">${rankHtml(g.rank)}</td>
            <td class="py-3.5 px-2 font-mono text-slate-500 text-xs">${g.visitor}</td>
            <td class="py-3.5 px-2"><span class="inline-block bg-slate-100 text-slate-700 font-bold text-sm px-3 py-0.5 rounded-lg">${g.skor}</span></td>
            <td class="py-3.5 px-2"><span class="inline-flex items-center justify-center bg-amber-100 text-amber-600 font-bold text-xs px-2.5 py-1 rounded-full">+${g.poin}</span></td>
            <td class="py-3.5 px-2 text-slate-400 text-xs">${g.waktu}</td>
        </tr>`;
            });
        })();
        // — mobile cards —
        (function() {
            const el = document.getElementById('game-cards');
            gameData.forEach(g => {
                el.innerHTML += `
        <div class="card-row py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex items-center">${rankHtml(g.rank)}</span>
                    <span class="font-mono text-slate-600 text-xs font-semibold">${g.visitor}</span>
                </div>
                <span class="inline-block bg-slate-100 text-slate-700 font-bold text-sm px-3 py-0.5 rounded-lg">${g.skor}</span>
            </div>
            <div class="flex items-center gap-2 mt-1.5 ml-6">
                <span class="inline-flex items-center justify-center bg-amber-100 text-amber-600 font-bold text-xs px-2.5 py-0.5 rounded-full">+${g.poin}</span>
                <span class="text-slate-400 text-xs">${g.waktu}</span>
            </div>
        </div>`;
            });
        })();

        // ══════ KOPI ══════
        // — desktop table —
        (function() {
            const tbody = document.getElementById('kopi-tbody');
            kopiData.forEach((k, i) => {
                tbody.innerHTML += `
        <tr class="border-t border-slate-50 hover:bg-slate-50 transition">
            <td class="py-3.5 px-2 text-slate-500">${i+1}</td>
            <td class="py-3.5 px-2 font-mono font-bold text-slate-700 text-sm">${k.kode}</td>
            <td class="py-3.5 px-2">${statusBadge(k.status)}</td>
            <td class="py-3.5 px-2 text-slate-600">${k.poin}</td>
            <td class="py-3.5 px-2 text-slate-400 text-xs">${k.dibuat}</td>
            <td class="py-3.5 px-2 text-slate-400 text-xs">${k.exp}</td>
        </tr>`;
            });
        })();
        // — mobile cards —
        (function() {
            const el = document.getElementById('kopi-cards');
            kopiData.forEach((k, i) => {
                el.innerHTML += `
        <div class="card-row py-3">
            <div class="flex items-center justify-between">
                <span class="font-mono font-bold text-slate-700 text-sm">${k.kode}</span>
                ${statusBadge(k.status)}
            </div>
            <div class="flex items-center gap-3 mt-1.5">
                <span class="text-slate-500 text-xs"><span class="text-slate-400">Poin:</span> ${k.poin}</span>
                <span class="text-slate-400 text-xs">Dibuat: ${k.dibuat}</span>
            </div>
            <div class="mt-0.5">
                <span class="text-slate-400 text-xs">Kadaluarsa: ${k.exp}</span>
            </div>
        </div>`;
            });
        })();

        // ══════ APEX CHARTS ══════

        // ── shared base configs ──
        const trendBase = {
            series: [{
                name: 'Pengunjung',
                data: [4, 10, 10, 6, 6, 9, 4, 7, 7, 4, 9, 5, 25, 2, 0, 2, 0, 2]
            }],
            chart: {
                type: 'area',
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#10b981'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    type: 'vertical',
                    colorFrom: '#10b981',
                    /* dark emerald at the peak */
                    colorTo: '#d1fae5',
                    /* light emerald at the base */
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: ['16 Jan', '17 Jan', '18 Jan', '19 Jan', '20 Jan', '21 Jan', '22 Jan', '23 Jan', '24 Jan',
                    '25 Jan', '26 Jan', '27 Jan', '28 Jan', '29 Jan', '30 Jan', '01 Feb', '02 Feb', '03 Feb'
                ],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '10px'
                    },
                    rotate: 0
                }
            },
            yaxis: {
                show: true,
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '11px'
                    }
                },
                min: 0
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDasharray: 4
            },
            tooltip: {
                theme: 'light'
            }
        };

        // ── 1. Tren – desktop (md+) ──
        new ApexCharts(document.querySelector('#chart-trend-desktop'), {
            ...trendBase,
            chart: {
                ...trendBase.chart,
                height: 220
            }
        }).render();

        // ── 2. Tren – mobile only ──
        new ApexCharts(document.querySelector('#chart-trend-mobile'), {
            ...trendBase,
            chart: {
                ...trendBase.chart,
                height: 180
            },
            xaxis: {
                ...trendBase.xaxis,
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '9px'
                    },
                    rotate: -30
                }
            }
        }).render();

        // ── 3. Gender ──
        new ApexCharts(document.querySelector('#chart-gender'), {
            series: [{
                data: [83, 47]
            }],
            chart: {
                type: 'bar',
                height: 130,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 5,
                    barHeight: '40%',
                    distributed: true
                }
            },
            colors: ['#3b82f6', '#3b82f6'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['Laki-laki', 'Perempuan'],
                labels: {
                    show: true,
                    style: {
                        colors: '#64748b',
                        fontSize: '11px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                min: 0,
                max: 110
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                }
            },
            grid: {
                show: false
            },
            legend: {
                show: false
            },
            tooltip: {
                theme: 'light'
            }
        }).render();

        // ── 4. Aktivitas ──
        new ApexCharts(document.querySelector('#chart-activity'), {
            series: [{
                name: 'Total',
                data: [108, 60, 80, 12]
            }],
            chart: {
                type: 'bar',
                height: 150,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            plotOptions: {
                bar: {
                    borderRadius: 5,
                    columnWidth: '55%',
                    distributed: true
                }
            },
            colors: ['#14b8a6', '#f59e0b', '#f97316', '#3b82f6'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: ['Scan', 'Foto', 'Game', 'Kopi'],
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '11px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                show: true,
                labels: {
                    style: {
                        colors: '#94a3b8',
                        fontSize: '10px'
                    }
                },
                min: 0
            },
            grid: {
                show: false
            },
            legend: {
                show: false
            },
            tooltip: {
                theme: 'light'
            }
        }).render();
    </script>
</body>

</html>
<?php /**PATH /var/www/html/resources/views/admin/analytics.blade.php ENDPATH**/ ?>