<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function show(Quiz $quiz): View
    {
        $user = Auth::user();

        $course = $quiz->course;
        $enrolled = QuizAttempt::query() // dummy query builder to keep variable usage consistent
            ->from('course_user')
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();
        abort_if(!$enrolled, 403);



        // Pastikan relasi questions tersedia untuk view.
        $quiz->load('questions');

        // Buat attempt baru saat siswa mulai quiz (jika sudah ada, tampilkan yang terakhir)
        $attempt = QuizAttempt::where('siswa_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->first();

        if (!$attempt) {
            $attempt = QuizAttempt::create([
                'siswa_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => 0,
                'certificate_path' => null,
            ]);
        }

        return view('siswa.quiz', compact('user', 'quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();

        $course = $quiz->course;
        $enrolled = QuizAttempt::query()
            ->from('course_user')
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        abort_if(!$enrolled, 403);


        $request->validate([
            'answers' => ['required', 'array'],
        ]);

        $quiz->load(['questions']);

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
                if (!empty($invalid)) continue;

                if ($given === $correct) {
                    $correctCount++;
                }
            } else {
                $given = strtoupper((string) $given);
                $correct = $question->correct_options ?? [];
                if ($given && in_array($given, array_map('strtoupper', $correct))) {
                    $correctCount++;
                }
            }
        }

        $scorePercent = $total > 0 ? (int) round(($correctCount / $total) * 100) : 0;
        $quizPassingScore = (int) ($quiz->passing_score ?? 70);
        $passed = $scorePercent >= $quizPassingScore;

        $attempt = QuizAttempt::where('siswa_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->firstOrFail();

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

        $attempt->load(['quiz.course', 'siswa']);

        return view('siswa.result', compact('user', 'attempt'));
    }
}
