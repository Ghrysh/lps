<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2.5 sm:gap-4 mb-5 sm:mb-6">
    <div class="card-blue p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
        <div class="text-blue-500 text-lg sm:text-2xl mb-1 sm:mb-2">
            <i class="fas fa-users"></i>
        </div>
        {{-- FIX: Tambahkan ['header'] --}}
        <h3 class="text-xl sm:text-3xl font-bold text-slate-800">{{ $stats['header']['pengunjung'] }}</h3>
        <p class="text-xs text-slate-500 mt-0.5">Total Pengunjung</p>
    </div>

    <div class="card-red p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
        <div class="text-red-400 text-lg sm:text-2xl mb-1 sm:mb-2">
            <i class="fas fa-qrcode"></i>
        </div>
        <h3 class="text-xl sm:text-3xl font-bold text-slate-800">{{ $stats['header']['scan'] }}</h3>
        <p class="text-xs text-slate-500 mt-0.5">Total Scan Panel</p>
    </div>

    <div class="card-amber p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
        <div class="text-amber-500 text-lg sm:text-2xl mb-1 sm:mb-2">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="text-xl sm:text-3xl font-bold text-slate-800">{{ number_format($stats['header']['poin']) }}</h3>
        <p class="text-xs text-slate-500 mt-0.5">Total Poin Terkumpul</p>
    </div>

    <div class="card-teal p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
        <div class="text-teal-500 text-lg sm:text-2xl mb-1 sm:mb-2">
            <i class="fas fa-camera"></i>
        </div>
        <h3 class="text-xl sm:text-3xl font-bold text-slate-800">{{ $stats['header']['foto'] }}</h3>
        <p class="text-xs text-slate-500 mt-0.5">Foto Booth</p>
    </div>

    <div class="card-green p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
        <div class="text-green-500 text-lg sm:text-2xl mb-1 sm:mb-2">
            <i class="fas fa-gamepad"></i>
        </div>
        <h3 class="text-xl sm:text-3xl font-bold text-slate-800">{{ $stats['header']['game'] }}</h3>
        <p class="text-xs text-slate-500 mt-0.5">Game Dimainkan</p>
    </div>

    <div class="card-rose p-3 sm:p-5 rounded-xl sm:rounded-2xl shadow-sm">
        <div class="text-rose-400 text-lg sm:text-2xl mb-1 sm:mb-2">
            <i class="fas fa-coffee"></i>
        </div>
        {{-- FIX: Langsung panggil string karena di controller formatnya '12/40' --}}
        <h3 class="text-xl sm:text-3xl font-bold text-slate-800">{{ $stats['header']['kopi'] }}</h3>
        <p class="text-xs text-slate-500 mt-0.5">
            Kode Kopi <span class="text-slate-400">ditukar</span>
        </p>
    </div>
</div>