<div class="md:hidden mb-4">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
            <i class="fas fa-chart-line text-emerald-500"></i> Tren Pengunjung
        </h3>
        <div id="chart-trend-mobile" style="min-height: 200px; width: 100%;"></div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="hidden md:block lg:col-span-2 bg-white p-5 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-chart-line text-emerald-500"></i> Tren Pengunjung
        </h3>
        <div id="chart-trend-desktop" style="min-height: 230px; width: 100%;"></div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex flex-col justify-between">
        <div>
            <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm sm:text-base">
                <i class="fas fa-venus-mars text-blue-500"></i> Gender
            </h3>
            <div id="chart-gender" style="min-height: 160px; width: 100%; margin: auto;"></div>
        </div>
        
        <div class="flex justify-between text-xs text-slate-400 mt-2 px-1 pt-2 border-t border-slate-50">
            <span>Total: <strong class="text-slate-700">{{ $stats['pengunjung'] ?? 0 }}</strong></span>
            <span>Avg: <strong class="text-blue-500">50%</strong></span>
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 flex flex-col">
        <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-bolt text-amber-500"></i> Aktivitas
        </h3>
        <div class="flex-grow flex items-center">
            <div id="chart-activity" style="min-height: 180px; width: 100%;"></div>
        </div>
    </div>
</div>