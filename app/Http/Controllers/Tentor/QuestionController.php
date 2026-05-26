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
    private function ensureQuizOwnership(Quiz $quiz): void
    {
        if ($quiz->course->tentor_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke quiz ini.');
        }
    }

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

    public function create(int|string $quizId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);
        $this->ensureQuizOwnership($quiz);

        return view('tentor.quizzes.questions.create', compact('quiz'));
    }

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
            ->with('success', 'Soal berhasil ditambahkan.');
    }

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
            ->with('success', 'Soal berhasil diperbarui.');
    }

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
            ->with('success', 'Soal berhasil dihapus.');
    }
}
