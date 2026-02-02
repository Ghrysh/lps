<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // --- HALAMAN GAME (USER) ---
    public function index()
    {
        // Ambil pertanyaan aktif, acak urutan soalnya
        $questions = Question::with('answers')
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(10) // Batasi misal 10 soal per sesi
            ->get();

        // Acak urutan jawaban (A,B,C,D) untuk setiap pertanyaan
        foreach ($questions as $q) {
            $q->shuffled_answers = $q->answers->shuffle();
        }

        return view('admin.tools.minigame', compact('questions'));
    }

    // --- HALAMAN MASTER DATA (ADMIN) ---
    public function manage()
    {
        $questions = Question::with('answers')->latest()->get();
        return view('admin.tools.quiz_manager', compact('questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required',
            'duration' => 'required|integer|min:5|max:300', // Validasi baru
            'answers' => 'required|array|min:2',
            'correct_index' => 'required',
        ]);

        // 1. Simpan Pertanyaan dengan Durasi
        $q = Question::create([
            'question_text' => $request->question_text,
            'duration' => $request->duration // Simpan durasi
        ]);

        // 2. Simpan Jawaban (Kode lama tetap sama)
        foreach ($request->answers as $index => $text) {
            if (!empty($text)) {
                $q->answers()->create([
                    'answer_text' => $text,
                    'is_correct' => ($index == $request->correct_index)
                ]);
            }
        }

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Question::destroy($id);
        return back()->with('success', 'Soal dihapus.');
    }
}
