<div x-data="{ activeTab: 'pengunjung' }"
    class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">

    <!-- HEADER -->
    <div class="px-6 pt-6">
        <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-file-alt text-emerald-600"></i> Laporan Detail
        </h3>

        <div class="grid grid-cols-4 gap-1.5 bg-slate-100 rounded-xl p-1">
            @foreach (['pengunjung','foto','game','kopi'] as $tab)
                <button @click="activeTab='{{ $tab }}'"
                    :class="activeTab==='{{ $tab }}' ? 'bg-white shadow text-slate-800' : 'text-slate-500'"
                    class="px-3 py-2 rounded-lg text-xs font-semibold capitalize">
                    {{ $tab }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- CONTENT -->
    <div class="p-6">

        <!-- ================= PENGUNJUNG ================= -->
        <div x-show="activeTab==='pengunjung'" x-cloak>
            <table class="w-full text-sm">
                <thead class="text-slate-400">
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Gender</th>
                        <th>Poin</th>
                        <th>Daftar</th>
                        <th>Aktif</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengunjung as $i => $row)
                        <tr class="border-t">
                            <td>{{ $i + 1 }}</td>
                            <td class="font-mono text-xs">{{ $row['id'] }}</td>
                            <td>
                                <span class="px-2 py-0.5 rounded-full text-xs
                                    {{ $row['gender']=='L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600' }}">
                                    {{ $row['gender'] }}
                                </span>
                            </td>
                            <td>{{ $row['poin'] }}</td>
                            <td class="text-xs text-slate-400">{{ $row['daftar'] }}</td>
                            <td class="text-xs text-slate-400">{{ $row['aktif'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ================= FOTO ================= -->
        <div x-show="activeTab==='foto'" x-cloak>
            @foreach ($kostum as $k)
                <div class="flex justify-between py-2 border-b text-sm">
                    <span>{{ $k['nama'] }}</span>
                    <span class="font-semibold">{{ $k['count'] }} foto</span>
                </div>
            @endforeach
        </div>

        <!-- ================= GAME ================= -->
        <div x-show="activeTab==='game'" x-cloak>
            <table class="w-full text-sm">
                @foreach ($games as $g)
                    <tr class="border-b">
                        <td>#{{ $g['rank'] }}</td>
                        <td class="font-mono text-xs">{{ $g['visitor'] }}</td>
                        <td>{{ $g['skor'] }}</td>
                        <td>{{ $g['poin'] }} poin</td>
                        <td class="text-xs text-slate-400">{{ $g['waktu'] }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <!-- ================= KOPI ================= -->
        <div x-show="activeTab==='kopi'" x-cloak>
            @foreach ($kopi as $k)
                <div class="flex justify-between border-b py-2 text-sm">
                    <span>{{ $k['kode'] }}</span>
                    <span class="font-semibold">{{ $k['status'] }}</span>
                </div>
            @endforeach
        </div>

    </div>
</div>
