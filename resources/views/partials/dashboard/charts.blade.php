<!-- ── CHARTS ── -->

<!-- MOBILE: Tren -->
<div class="md:hidden mb-3">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
            <i class="fas fa-chart-line text-emerald-500"></i> Tren Pengunjung
        </h3>
        <div id="chart-trend-mobile"></div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">

    <!-- DESKTOP: Tren -->
    <div
        class="hidden md:block lg:col-span-2 bg-white p-4 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm sm:text-base">
            <i class="fas fa-chart-line text-emerald-500"></i> Tren Pengunjung
        </h3>
        <div id="chart-trend-desktop"></div>
    </div>

    <!-- Gender -->
    <div class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
            <i class="fas fa-venus-mars text-amber-500"></i> Gender
        </h3>
        <div id="chart-gender" class="flex-1 flex items-center justify-center"></div>
        <div class="flex justify-between text-xs text-slate-400 mt-2">
            <span>Total: <strong class="text-slate-600">130</strong></span>
            <span>Avg Poin: <strong class="text-amber-500">80</strong></span>
        </div>
    </div>

    <!-- Aktivitas -->
    <div class="bg-white p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2 text-sm">
            <i class="fas fa-bolt text-rose-500"></i> Aktivitas
        </h3>
        <div id="chart-activity" class="flex-1 flex items-center justify-center"></div>
    </div>
</div>
