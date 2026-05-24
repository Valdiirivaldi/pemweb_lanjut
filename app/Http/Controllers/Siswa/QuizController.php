<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'answers.*' => ['nullable', 'string', 'in:A,B,C,D'],
        ]);

        $quiz->load(['questions']);

        $total = $quiz->questions->count();
        $correctCount = 0;

        foreach ($quiz->questions as $question) {
            $given = $request->input('answers.' . $question->id);
            if ($given && strtoupper($given) === strtoupper($question->correct_option)) {
                $correctCount++;
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
            // placeholder PDF: simpan file teks dengan ekstensi .pdf
            $fileName = 'certificate_' . $user->id . '_' . $quiz->id . '_' . Str::random(8) . '.pdf';
            $relativeDir = 'certificates';
            $storagePath = storage_path('app/' . $relativeDir);

            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0775, true);
            }

            $absolute = $storagePath . DIRECTORY_SEPARATOR . $fileName;
            file_put_contents(
                $absolute,
                "Certificate (placeholder PDF)\n\nName: {$user->name}\nQuiz: {$quiz->title}\nScore: {$scorePercent}%\nPassing: {$quizPassingScore}%\n"
            );

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
