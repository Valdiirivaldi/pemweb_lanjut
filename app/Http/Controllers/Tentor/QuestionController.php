<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(int|string $quizId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);

        $questions = Question::query()
            ->where('quiz_id', $quiz->id)
            ->latest('id')
            ->get();

        return view('tentor.quizzes.questions.index', compact('quiz', 'questions'));
    }

    public function create(int|string $quizId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);

        return view('tentor.quizzes.questions.create', compact('quiz'));
    }

    public function store(Request $request, int|string $quizId): RedirectResponse
    {
        $quiz = Quiz::query()->findOrFail($quizId);

        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:10000'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'option_a' => $validated['option_a'],
            'option_b' => $validated['option_b'],
            'option_c' => $validated['option_c'],
            'option_d' => $validated['option_d'],
            // Pastikan selalu huruf besar agar sinkron dengan logika siswa:
            // strtoupper($given) === strtoupper($question->correct_option)
            'correct_option' => strtoupper($validated['correct_option']),
        ]);

        return redirect()
            ->route('tentor.quizzes.questions.index', ['quiz' => $quiz->id])
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(int|string $quizId, int|string $questionId): View
    {
        $quiz = Quiz::query()->findOrFail($quizId);

        $question = Question::query()
            ->where('quiz_id', $quiz->id)
            ->where('id', $questionId)
            ->firstOrFail();

        return view('tentor.quizzes.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, int|string $quizId, int|string $questionId): RedirectResponse
    {
        $quiz = Quiz::query()->findOrFail($quizId);

        $question = Question::query()
            ->where('quiz_id', $quiz->id)
            ->where('id', $questionId)
            ->firstOrFail();

        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:10000'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'option_a' => $validated['option_a'],
            'option_b' => $validated['option_b'],
            'option_c' => $validated['option_c'],
            'option_d' => $validated['option_d'],
            'correct_option' => strtoupper($validated['correct_option']),
        ]);

        return redirect()
            ->route('tentor.quizzes.questions.index', ['quiz' => $quiz->id])
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(int|string $quizId, int|string $questionId): RedirectResponse
    {
        $quiz = Quiz::query()->findOrFail($quizId);

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
