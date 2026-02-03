@extends('layouts.admin')

@section('title', 'Dashboard LPS')

@section('content')
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
    <!-- ── HEADER ── -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-5 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
                Dashboard Analitik LPS
            </h2>
            <p class="text-slate-400 text-xs sm:text-sm">
                Monitoring aktivitas & interaksi pengunjung secara real-time
            </p>
        </div>

        <!-- Controls -->
        <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center w-full md:w-auto">
            <div class="flex gap-2 items-center flex-wrap">
                <div class="flex bg-white rounded-lg border border-slate-200 p-1 shadow-sm">
                    <button class="px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 rounded-md">
                        Hari Ini
                    </button>
                    <button class="px-3 py-1.5 text-xs font-medium text-slate-500 hover:bg-slate-100 rounded-md">
                        7 Hari
                    </button>
                    <button class="px-3 py-1.5 text-xs font-bold bg-emerald-600 text-white rounded-md">
                        Semua
                    </button>
                </div>

                <button
                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 shadow-sm">
                    <i class="fas fa-calendar-alt"></i> Pilih Tanggal
                </button>
            </div>

            <button
                class="flex items-center gap-1.5 px-4 py-1.5 text-xs sm:text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm w-full sm:w-auto justify-center">
                <i class="fas fa-file-alt"></i> Export Semua
            </button>
        </div>
    </div>

    {{-- ================= STAT CARDS ================= --}}
    {{-- PAKAI PERSIS YANG ATAS --}}
    @include('partials.dashboard.stat-cards')

    {{-- ================= CHARTS ================= --}}
    @include('partials.dashboard.charts')

    {{-- ================= LAPORAN DETAIL + TAB ================= --}}
    @include('partials.dashboard.tabs')
@endsection
