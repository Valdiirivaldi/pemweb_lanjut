<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $quizAttempts = $user->quizAttempts()
            ->with('quiz.course')
            ->latest()
            ->get();

        return view('student.quizzes.index', compact('user', 'quizAttempts'));
    }

    public function show(Quiz $quiz): View
    {
        $user = Auth::user();

        $course = $quiz->course;
        $enrolled = $course->students()->where('user_id', $user->id)->exists();
        abort_if(!$enrolled, 403);

        $quiz->load('questions');

        $attempt = QuizAttempt::firstOrCreate([
            'siswa_id' => $user->id,
            'quiz_id' => $quiz->id,
        ], [
            'score' => 0,
            'certificate_path' => null,
        ]);

        if ($attempt->score > 0 || $attempt->answers()->exists()) {
            return redirect()->route('siswa.quiz-attempts.show', ['attempt' => $attempt->id]);
        }

        if ($attempt->wasRecentlyCreated === false) {
            $attempt->created_at = now();
            $attempt->save();
            $attempt->refresh();
        }

        return view('student.quizzes.take', compact('user', 'quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();

        $course = $quiz->course;
        $enrolled = $course->students()->where('user_id', $user->id)->exists();

        abort_if(!$enrolled, 403);

        $request->validate([
            'answers' => ['nullable', 'array'],
        ]);

        $quiz->load(['questions']);

        $attempt = QuizAttempt::firstOrCreate([
            'siswa_id' => $user->id,
            'quiz_id' => $quiz->id,
        ], [
            'score' => 0,
            'certificate_path' => null,
        ]);

        if ($attempt->score > 0 || $attempt->answers()->count() > 0) {
            return redirect()->route('siswa.quiz-attempts.show', ['attempt' => $attempt->id]);
        }

        if ($quiz->time_limit) {
            $startTime = $attempt->created_at;
            $maxDeadline = $startTime->copy()->addMinutes((int) $quiz->time_limit + 5);
            if (now()->greaterThan($maxDeadline)) {
                return redirect()->route('siswa.quiz-attempts.show', ['attempt' => $attempt->id])
                    ->with('error', 'Waktu pengerjaan kuis telah habis.');
            }
        }

        QuizAttemptAnswer::where('quiz_attempt_id', $attempt->id)->delete();

        $total = $quiz->questions->count();
        $correctCount = 0;

        foreach ($quiz->questions as $question) {
            $given = $request->input('answers.' . $question->id);

            if ($question->isMultipleChoice()) {
                $given = (array) $given;
                $given = array_map('strtoupper', $given);
                sort($given);
                $correct = $question->correct_options ?? [];
                $correct = array_map('strtoupper', $correct);
                sort($correct);

                $validKeys = array_keys($question->options ?? []);
                $invalid = array_diff($given, $validKeys);
                if (!empty($invalid)) {
                    QuizAttemptAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'given_answer' => json_encode($given),
                        'is_correct' => false,
                    ]);
                    continue;
                }

                $isCorrect = $given === $correct;
                if ($isCorrect) {
                    $correctCount++;
                }

                QuizAttemptAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'given_answer' => json_encode($given),
                    'is_correct' => $isCorrect,
                ]);
            } else {
                $given = strtoupper((string) $given);
                $correct = $question->correct_options ?? [];

                $isCorrect = $given && in_array($given, array_map('strtoupper', $correct));
                if ($isCorrect) {
                    $correctCount++;
                }

                QuizAttemptAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'given_answer' => json_encode([$given]),
                    'is_correct' => $isCorrect,
                ]);
            }
        }

        $scorePercent = $total > 0 ? (int) round(($correctCount / $total) * 100) : 0;
        $quizPassingScore = (int) ($quiz->passing_score ?? 70);
        $passed = $scorePercent >= $quizPassingScore;

        $certificatePath = null;
        if ($passed) {
            $fileName = 'certificate_' . $user->id . '_' . $quiz->id . '_' . Str::random(8) . '.pdf';
            $relativeDir = 'certificates';
            $storagePath = storage_path('app/public/' . $relativeDir);

            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0775, true);
            }

            $pdf = Pdf::loadView('certificates.template', [
                'studentName' => $user->name,
                'quizTitle' => $quiz->title,
                'courseTitle' => $quiz->course->title,
                'score' => $scorePercent,
                'date' => now()->format('j F Y'),
            ]);

            $pdf->save($storagePath . '/' . $fileName);

            $certificatePath = $relativeDir . '/' . $fileName;
        }

        $attempt->update([
            'score' => $scorePercent,
            'certificate_path' => $certificatePath,
        ]);

        return redirect()->route('siswa.quiz-attempts.show', ['attempt' => $attempt->id]);
    }

    public function result(QuizAttempt $attempt): View
    {
        $user = Auth::user();
        abort_if($attempt->siswa_id !== $user->id, 403);

        $attempt->load(['quiz.course', 'siswa', 'answers.question']);

        return view('student.quizzes.result', compact('user', 'attempt'));
    }
}
