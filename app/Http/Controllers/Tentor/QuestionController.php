<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Memastikan bahwa kuis adalah milik tentor yang sedang login.
     * Jika bukan, membatalkan aksi dengan status 403 Forbidden.
     *
     * @param  Quiz  $quiz  Kuis yang akan divalidasi pemilikannya
     */
    private function ensureQuizOwnership(Quiz $quiz): void
    {
        if ($quiz->course->tentor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke quiz ini.');
        }
    }

    /**
     * Menampilkan daftar semua soal dalam sebuah kuis.
     * Soal diurutkan berdasarkan ID terbaru.
     */
    public function index(int|string $quizId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        $questions = Question::query()
            ->where('quiz_id', $quiz->id)
            ->latest('id')
            ->get();

        return view('tentor.quizzes.questions.index', compact('quiz', 'questions'));
    }

    /**
     * Menampilkan formulir untuk membuat soal baru dalam kuis.
     */
    public function create(int|string $quizId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        return view('tentor.quizzes.questions.create', compact('quiz'));
    }

    /**
     * Menyimpan soal baru ke database.
     * Mendukung 3 tipe soal: single (pilihan ganda), multiple (ganda ganda), true_false (benar/salah).
     * Memvalidasi bahwa kunci jawaban benar sesuai dengan kunci opsi yang tersedia.
     * Untuk tipe single dan true_false, hanya boleh satu jawaban benar.
     */
    public function store(Request $request, int|string $quizId): RedirectResponse
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:10000'],
            'type' => ['required', 'in:single,multiple,true_false'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string', 'max:255'],
            'correct_options' => ['required', 'array', 'min:1'],
            'correct_options.*' => ['required', 'string'],
        ]);

        $options = $validated['options'];
        $correctOptions = $validated['correct_options'];

        // Pastikan semua correct_options ada di keys options
        $validKeys = array_keys($options);
        foreach ($correctOptions as $key) {
            if (!in_array($key, $validKeys)) {
                return back()->withErrors(['correct_options' => "Invalid correct option: $key"])->withInput();
            }
        }

        // Untuk single/true_false, hanya boleh 1 correct option
        if (in_array($validated['type'], ['single', 'true_false']) && count($correctOptions) > 1) {
            return back()->withErrors(['correct_options' => 'Only one correct answer allowed for this question type.'])->withInput();
        }

        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'options' => $options,
            'correct_options' => array_map('strtoupper', $correctOptions),
        ]);

        return redirect()
            ->route('tentor.quizzes.questions.index', ['quiz' => $quiz->id])
            ->with('success', __('messages.question.created'));
    }

    /**
     * Menampilkan formulir untuk mengedit soal yang sudah ada.
     */
    public function edit(int|string $quizId, int|string $questionId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        $question = Question::query()
            ->where('quiz_id', $quiz->id)
            ->where('id', $questionId)
            ->firstOrFail();

        return view('tentor.quizzes.questions.edit', compact('quiz', 'question'));
    }

    /**
     * Memperbarui soal yang sudah ada di database.
     * Menggunakan validasi yang sama dengan store().
     */
    public function update(Request $request, int|string $quizId, int|string $questionId): RedirectResponse
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        $question = Question::query()
            ->where('quiz_id', $quiz->id)
            ->where('id', $questionId)
            ->firstOrFail();

        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:10000'],
            'type' => ['required', 'in:single,multiple,true_false'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string', 'max:255'],
            'correct_options' => ['required', 'array', 'min:1'],
            'correct_options.*' => ['required', 'string'],
        ]);

        $options = $validated['options'];
        $correctOptions = $validated['correct_options'];

        $validKeys = array_keys($options);
        foreach ($correctOptions as $key) {
            if (!in_array($key, $validKeys)) {
                return back()->withErrors(['correct_options' => "Invalid correct option: $key"])->withInput();
            }
        }

        if (in_array($validated['type'], ['single', 'true_false']) && count($correctOptions) > 1) {
            return back()->withErrors(['correct_options' => 'Only one correct answer allowed for this question type.'])->withInput();
        }

        $question->update([
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'options' => $options,
            'correct_options' => array_map('strtoupper', $correctOptions),
        ]);

        return redirect()
            ->route('tentor.quizzes.questions.index', ['quiz' => $quiz->id])
            ->with('success', __('messages.question.updated'));
    }

    /**
     * Menghapus soal secara permanen dari database.
     */
    public function destroy(int|string $quizId, int|string $questionId): RedirectResponse
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        $question = Question::query()
            ->where('quiz_id', $quiz->id)
            ->where('id', $questionId)
            ->firstOrFail();

        $question->delete();

        return redirect()
            ->route('tentor.quizzes.questions.index', ['quiz' => $quiz->id])
            ->with('success', __('messages.question.deleted'));
    }
}
