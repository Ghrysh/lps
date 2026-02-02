@extends('layouts.admin')
@section('title', 'Master Data Kuis')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Input Soal --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Tambah Soal Baru</h3>
                <form action="{{ route('tools.quiz_store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-600 mb-2">Pertanyaan</label>
                        <textarea name="question_text" rows="3" class="w-full rounded-xl border-slate-200" required
                            placeholder="Contoh: Apa kepanjangan LPS?"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-600 mb-2">Durasi Waktu (Detik)</label>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-stopwatch text-orange-600 text-xl"></i>
                            <input type="number" name="duration" value="20" min="5" max="300"
                                class="w-full rounded-xl border-slate-200" required>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">*Semakin sulit soal, berikan waktu lebih lama.</p>
                    </div>

                    <div class="mb-4 space-y-3">
                        <label class="block text-sm font-bold text-slate-600">Pilihan Jawaban</label>

                        @foreach (range(0, 3) as $i)
                            <div class="flex items-center gap-2">
                                <input type="radio" name="correct_index" value="{{ $i }}" required
                                    title="Pilih ini sebagai kunci jawaban">
                                <input type="text" name="answers[]" class="w-full rounded-lg border-slate-200 text-sm"
                                    placeholder="Pilihan {{ chr(65 + $i) }}" required>
                            </div>
                        @endforeach
                        <p class="text-xs text-slate-400">*Klik radio button bulat untuk menandai kunci jawaban.</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-600 text-white py-2 rounded-xl font-bold hover:bg-orange-700">Simpan
                        Soal</button>
                </form>
            </div>
        </div>

        {{-- List Soal --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-4">Daftar Soal ({{ $questions->count() }})</h3>
                <div class="space-y-4">
                    @foreach ($questions as $q)
                        <div class="border p-4 rounded-xl hover:bg-slate-50">
                            <div class="flex justify-between items-start">
                                <p class="font-bold text-slate-700">{{ $q->question_text }}</p>
                                <form action="{{ route('tools.quiz_delete', $q->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus soal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                            <ul class="mt-2 text-sm grid grid-cols-2 gap-2">
                                @foreach ($q->answers as $ans)
                                    <li class="{{ $ans->is_correct ? 'text-green-600 font-bold' : 'text-slate-500' }}">
                                        {{ $ans->is_correct ? '✓' : '•' }} {{ $ans->answer_text }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
