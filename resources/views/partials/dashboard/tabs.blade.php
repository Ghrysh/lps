<div x-data="{ activeTab: 'pengunjung' }" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">

    <div class="px-6 pt-6 pb-2">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                <i class="far fa-file-alt text-emerald-600"></i> Laporan Detail
            </h3>
        </div>

        <div class="bg-slate-50 p-1.5 rounded-xl grid grid-cols-4 gap-1">
            <button @click="activeTab = 'pengunjung'"
                :class="activeTab === 'pengunjung' ? 'bg-white text-slate-800 shadow-sm font-bold' :
                    'text-slate-500 hover:text-slate-700 font-medium'"
                class="flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm transition-all duration-200">
                <i class="far fa-user"></i> Pengunjung
            </button>
            <button @click="activeTab = 'foto'"
                :class="activeTab === 'foto' ? 'bg-white text-slate-800 shadow-sm font-bold' :
                    'text-slate-500 hover:text-slate-700 font-medium'"
                class="flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm transition-all duration-200">
                <i class="fas fa-camera"></i> Foto Booth
            </button>
            <button @click="activeTab = 'game'"
                :class="activeTab === 'game' ? 'bg-white text-slate-800 shadow-sm font-bold' :
                    'text-slate-500 hover:text-slate-700 font-medium'"
                class="flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm transition-all duration-200">
                <i class="fas fa-gamepad"></i> Game
            </button>
            <button @click="activeTab = 'kopi'"
                :class="activeTab === 'kopi' ? 'bg-white text-slate-800 shadow-sm font-bold' :
                    'text-slate-500 hover:text-slate-700 font-medium'"
                class="flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm transition-all duration-200">
                <i class="fas fa-coffee"></i> Kopi
            </button>
        </div>
    </div>

    <div class="p-6 min-h-[400px]">

        <div x-show="activeTab === 'pengunjung'" x-cloak>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex gap-3">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-700 shadow-sm">
                        <i class="fas fa-users text-slate-400"></i> Total: {{ $stats['tabs']['pengunjung']['total'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-blue-100 bg-blue-50 text-xs font-bold text-blue-600 shadow-sm">
                        <i class="fas fa-male"></i> L: {{ $stats['tabs']['pengunjung']['l'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-pink-100 bg-pink-50 text-xs font-bold text-pink-600 shadow-sm">
                        <i class="fas fa-female"></i> P: {{ $stats['tabs']['pengunjung']['p'] }}
                    </span>
                </div>
                <button
                    class="flex items-center gap-2 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fas fa-download"></i> Export Excel
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-white text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Session ID</th>
                            <th class="px-6 py-4">Gender</th>
                            <th class="px-6 py-4">Poin</th>
                            <th class="px-6 py-4">Waktu Daftar</th>
                            <th class="px-6 py-4 text-right">Terakhir Aktif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach ($pengunjung as $idx => $row)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-slate-600">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4 font-mono text-slate-500 text-xs">{{ $row['id'] }}</td>
                                <td class="px-6 py-4">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $row['gender'] == 'L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                                        {{ $row['gender'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-orange-100 text-orange-600">
                                        {{ $row['poin'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $row['daftar'] }}</td>
                                <td class="px-6 py-4 text-right text-slate-500">{{ $row['aktif'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'foto'" x-cloak>

            <div class="flex justify-end mb-6">
                <button
                    class="flex items-center gap-2 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fas fa-download"></i> Export Excel
                </button>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 p-6 mb-6">
                <h4 class="font-bold text-slate-800 text-sm mb-6 font-serif">Distribusi Kostum Daerah</h4>
                <div class="space-y-5">
                    @foreach ($kostum as $k)
                        <div>
                            <div class="flex justify-between text-xs mb-2">
                                <span class="font-semibold text-slate-700 flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $k['color'] }}"></span>
                                    {{ $k['nama'] }}
                                </span>
                                <span class="text-slate-400 font-medium">{{ $k['count'] }}
                                    ({{ number_format($k['persen'], 1) }}%)</span>
                            </div>
                            <div class="w-full bg-[#f1f5f9] rounded-full h-2.5">
                                <div class="h-2.5 rounded-full {{ $k['color'] }}"
                                    style="width: {{ $k['persen'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-white border-b border-slate-100">
                    <h4 class="font-bold text-slate-800 text-sm font-serif">Riwayat Foto Booth Terbaru</h4>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Kostum Daerah</th>
                            <th class="px-6 py-3 text-center">Poin Digunakan</th>
                            <th class="px-6 py-3 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm bg-white">
                        @if (isset($riwayatFoto) && count($riwayatFoto) > 0)
                            @foreach ($riwayatFoto as $idx => $rf)
                                <tr>
                                    <td class="px-6 py-4 text-slate-600">{{ $idx + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md {{ $rf['bg_color'] }} border {{ $rf['border_color'] }} text-xs font-bold {{ $rf['text_color'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $rf['dot_color'] }}"></span>
                                            {{ $rf['kostum'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $rf['poin'] }}</td>
                                    <td class="px-6 py-4 text-right text-slate-500">{{ $rf['waktu'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center py-4 text-slate-400">Belum ada data.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'game'" x-cloak>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex gap-3">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-bold text-slate-700 shadow-sm">
                        Total: {{ $stats['tabs']['game']['total'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-amber-100 bg-amber-50 text-xs font-bold text-amber-600 shadow-sm">
                        Avg: {{ $stats['tabs']['game']['avg'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-green-100 bg-green-50 text-xs font-bold text-green-600 shadow-sm">
                        Poin: {{ $stats['tabs']['game']['poin'] }}
                    </span>
                </div>
                <button
                    class="flex items-center gap-2 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-white text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Rank</th>
                            <th class="px-6 py-4">Visitor</th>
                            <th class="px-6 py-4 text-center">Skor</th>
                            <th class="px-6 py-4 text-center">Poin</th>
                            <th class="px-6 py-4 text-right">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach ($games as $g)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    @if ($g['rank'] == 1)
                                        <i class="fas fa-trophy text-yellow-500 text-lg"></i>
                                    @elseif($g['rank'] == 2)
                                        <i class="fas fa-trophy text-slate-400 text-lg"></i>
                                    @elseif($g['rank'] == 3)
                                        <i class="fas fa-trophy text-amber-700 text-lg"></i>
                                    @else
                                        <span class="font-bold text-slate-600 ml-1.5">{{ $g['rank'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-500 text-xs">{{ $g['visitor'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-block bg-slate-100 text-slate-800 font-bold px-3 py-1 rounded-md border border-slate-200">
                                        {{ $g['skor'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full text-xs font-bold border border-amber-100">
                                        +{{ $g['poin'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-500">{{ $g['waktu'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'kopi'" x-cloak>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex gap-3">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 bg-white text-xs font-bold text-slate-700 shadow-sm">
                        Total: {{ $stats['tabs']['kopi']['total'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-green-100 bg-green-50 text-xs font-bold text-green-600 shadow-sm">
                        <i class="fas fa-check"></i> {{ $stats['tabs']['kopi']['ditukar'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-amber-100 bg-amber-50 text-xs font-bold text-amber-600 shadow-sm">
                        <i class="fas fa-circle"></i> {{ $stats['tabs']['kopi']['pending'] }}
                    </span>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-red-100 bg-red-50 text-xs font-bold text-red-600 shadow-sm">
                        <i class="fas fa-times-circle"></i> {{ $stats['tabs']['kopi']['expired'] }}
                    </span>
                </div>
                <button
                    class="flex items-center gap-2 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-100">
                <table class="w-full text-left border-collapse">
                    <thead
                        class="bg-white text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Poin</th>
                            <th class="px-6 py-4">Dibuat</th>
                            <th class="px-6 py-4 text-right">Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @foreach ($kopi as $idx => $k)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 font-medium text-slate-600">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-700 text-xs">{{ $k['kode'] }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($k['status'] == 'Ditukar')
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-green-50 border border-green-200 text-green-600 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                            <i class="fas fa-check-circle"></i> Ditukar
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-600 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                            <i class="fas fa-clock"></i> Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600 font-medium">{{ $k['poin'] }}</td>
                                <td class="px-6 py-4 text-slate-500 text-xs">{{ $k['dibuat'] }}</td>
                                <td class="px-6 py-4 text-right text-slate-500 text-xs">{{ $k['exp'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
