@extends('layouts.admin')

@section('title', 'Dashboard Analitik')

@section('content')
    <style>
        /* Custom Gradients for Cards */
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

        /* Tab Styles */
        .tab-active {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            color: #1e293b;
        }

        .tab-inactive {
            background: #f1f5f9;
            color: #64748b;
        }

        /* Mobile Table Fixes */
        .card-row {
            border-top: 1px solid #f1f5f9;
        }

        .card-row:first-child {
            border-top: none;
        }

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

        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4" x-data="{ dateFilter: 'semua' }">

        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
                Dashboard Analitik
            </h2>
            <p class="text-slate-400 text-xs sm:text-sm">
                Monitor aktivitas & interaksi pengunjung secara real-time
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-center w-full md:w-auto">
            <div class="flex gap-2 items-center flex-wrap">

                <div class="flex bg-white rounded-lg border border-slate-200 p-1 shadow-sm">

                    <button @click="dateFilter = 'today'"
                        :class="dateFilter === 'today' ? 'bg-emerald-600 text-white shadow-sm font-bold' :
                            'text-slate-500 hover:bg-slate-100 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-md transition-all duration-200">
                        Hari Ini
                    </button>

                    <button @click="dateFilter = '7_hari'"
                        :class="dateFilter === '7_hari' ? 'bg-emerald-600 text-white shadow-sm font-bold' :
                            'text-slate-500 hover:bg-slate-100 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-md transition-all duration-200">
                        7 Hari
                    </button>

                    <button @click="dateFilter = '30_hari'"
                        :class="dateFilter === '30_hari' ? 'bg-emerald-600 text-white shadow-sm font-bold' :
                            'text-slate-500 hover:bg-slate-100 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-md transition-all duration-200">
                        30 Hari
                    </button>

                    <button @click="dateFilter = 'semua'"
                        :class="dateFilter === 'semua' ? 'bg-emerald-600 text-white shadow-sm font-bold' :
                            'text-slate-500 hover:bg-slate-100 font-medium'"
                        class="px-3 py-1.5 text-xs rounded-md transition-all duration-200">
                        Semua
                    </button>

                </div>

                <div class="relative">
                    <input type="date" x-ref="dateInput"
                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10"
                        @change="dateFilter = 'custom'">

                    <button
                        :class="dateFilter === 'custom' ? 'border-emerald-500 text-emerald-700 bg-emerald-50' :
                            'border-slate-200 text-slate-600 bg-white hover:bg-slate-50'"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium border rounded-lg shadow-sm transition active:scale-95">
                        <i class="fas fa-calendar-alt"
                            :class="dateFilter === 'custom' ? 'text-emerald-600' : 'text-slate-400'"></i>
                        <span x-text="dateFilter === 'custom' ? 'Tanggal Dipilih' : 'Pilih Tanggal'"></span>
                    </button>
                </div>

            </div>

            <button onclick="exportData()"
                class="flex items-center gap-1.5 px-4 py-1.5 text-xs sm:text-sm font-bold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm w-full sm:w-auto justify-center transition active:scale-95">
                <i class="fas fa-file-alt"></i> Export Semua
            </button>
        </div>
    </div>

    {{-- PARTIALS INCLUDE --}}

    {{-- 1. Kartu Statistik Atas --}}
    @include('partials.dashboard.stat-cards')

    {{-- 2. Grafik (Grid Layout) --}}
    @include('partials.dashboard.charts')

    {{-- 3. Tabel Detail (Tabs) --}}
    @include('partials.dashboard.tabs')

@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Dashboard Loaded. Inisialisasi Grafik...");

            const fontFamily = 'Plus Jakarta Sans, sans-serif';

            // --- CEK DATA DARI CONTROLLER ---
            const dataTrend = @json($chartTrend ?? ['labels' => [], 'data' => []]);
            const dataGender = @json($chartGender ?? ['labels' => [], 'data' => []]);
            const dataActivity = @json($chartActivity ?? ['labels' => [], 'data' => []]);

            console.log("Data Trend:", dataTrend); // Cek di Console browser (F12)

            // ── 1. CHART TREN ──
            const trendOptions = {
                series: [{
                    name: 'Pengunjung',
                    data: dataTrend.data
                }],
                chart: {
                    type: 'area',
                    height: 230, // Angka Fix
                    toolbar: {
                        show: false
                    },
                    fontFamily: fontFamily,
                    parentHeightOffset: 0
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
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                xaxis: {
                    categories: dataTrend.labels,
                    labels: {
                        style: {
                            colors: '#94a3b8',
                            fontSize: '10px'
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
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 10,
                        right: 0
                    }
                },
                tooltip: {
                    theme: 'light'
                }
            };

            if (document.querySelector("#chart-trend-desktop")) {
                new ApexCharts(document.querySelector("#chart-trend-desktop"), trendOptions).render();
            }

            // Mobile Version
            if (document.querySelector("#chart-trend-mobile")) {
                const mobileOpts = JSON.parse(JSON.stringify(trendOptions));
                mobileOpts.chart.height = 200;
                mobileOpts.xaxis.labels.rotate = -30;
                new ApexCharts(document.querySelector("#chart-trend-mobile"), mobileOpts).render();
            }

            // ── 2. CHART GENDER ──
            const genderOptions = {
                series: [{
                    data: dataGender.data
                }],
                chart: {
                    type: 'bar',
                    height: 160, // Angka Fix
                    toolbar: {
                        show: false
                    },
                    fontFamily: fontFamily,
                    parentHeightOffset: 0
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4,
                        barHeight: '40%',
                        distributed: true
                    }
                },
                colors: ['#3b82f6', '#ec4899'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: dataGender.labels,
                    labels: {
                        show: true,
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
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
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '11px',
                            fontWeight: 600
                        }
                    }
                },
                grid: {
                    show: false,
                    padding: {
                        top: 0,
                        bottom: 0,
                        left: 0,
                        right: 0
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: 'light'
                }
            };
            if (document.querySelector("#chart-gender")) {
                new ApexCharts(document.querySelector("#chart-gender"), genderOptions).render();
            }

            // ── 3. CHART AKTIVITAS ──
            const activityOptions = {
                series: [{
                    name: 'Total',
                    data: dataActivity.data
                }],
                chart: {
                    type: 'bar',
                    height: 180, // Angka Fix
                    toolbar: {
                        show: false
                    },
                    fontFamily: fontFamily,
                    parentHeightOffset: 0
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                        distributed: true
                    }
                },
                colors: ['#14b8a6', '#f59e0b', '#f97316', '#3b82f6'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: dataActivity.labels,
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '10px'
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
                    }
                },
                grid: {
                    show: false,
                    padding: {
                        top: 0,
                        bottom: 0
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: 'light'
                }
            };
            if (document.querySelector("#chart-activity")) {
                new ApexCharts(document.querySelector("#chart-activity"), activityOptions).render();
            }
        });
    </script>
@endpush
