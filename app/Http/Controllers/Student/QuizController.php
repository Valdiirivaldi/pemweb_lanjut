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
    /**
     * Menampilkan daftar semua percobaan kuis yang sudah dikerjakan oleh siswa.
     * Data dikelompokkan berdasarkan kuis dan diberi nomor urut percobaan (attempt_number).
     */
    public function index()
    {
        $user = Auth::user();

        $quizAttempts = $user->quizAttempts()
            ->with('quiz.course')
            ->latest()
            ->get()
            ->groupBy('quiz_id')
            ->map(function ($attempts) {
                return $attempts->values()->map(function ($attempt, $index) {
                    $attempt->attempt_number = $index + 1;
                    return $attempt;
                });
            })
            ->flatten(1)
            ->sortByDesc('created_at');

        return view('student.quizzes.index', compact('user', 'quizAttempts'));
    }

    /**
     * Menampilkan halaman pengerjaan kuis.
     * Memvalidasi bahwa siswa terdaftar di kelas kuis tersebut.
     * Membuat sesi percobaan baru jika belum ada sesi yang aktif (finished_at = null).
     */
    public function show(Quiz $quiz): View
    {
        $user = Auth::user();

        $course = $quiz->course;
        $enrolled = $course->students()->where('user_id', $user->id)->exists();
        abort_if(!$enrolled, 403);

        $quiz->load('questions');

        $attempt = QuizAttempt::where('siswa_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->whereNull('finished_at')
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

        return view('student.quizzes.take', compact('user', 'quiz', 'attempt'));
    }

    /**
     * Memproses pengiriman jawaban kuis dari siswa.
     * Alur kerja:
     * 1. Validasi sesi kuis yang aktif
     * 2. Cek apakah waktu pengerjaan sudah habis (time_limit + 5 menit grace period)
     * 3. Hapus jawaban sebelumnya, lalu grade semua jawaban baru
     * 4. Hitung skor persentase (benar/total * 100)
     * 5. Jika lulus (skor >= passing_score), buat sertifikat PDF menggunakan DomPDF
     * 6. Simpan percobaan dengan skor, path sertifikat, dan waktu selesai
     *
     * Mendukung 3 tipe soal: single, multiple, true_false.
     */
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

        $attempt = QuizAttempt::where('siswa_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->whereNull('finished_at')
            ->latest()
            ->first();

        if (!$attempt) {
            return redirect()->route('siswa.quizzes.show', $quiz)
                ->with('error', __('messages.error.no_active_quiz'));
        }

        $timeExpired = false;
        if ($quiz->time_limit) {
            $startTime = $attempt->created_at;
            $maxDeadline = $startTime->copy()->addMinutes((int) $quiz->time_limit + 5);
            if (now()->greaterThan($maxDeadline)) {
                $timeExpired = true;
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
            try {
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
            } catch (\Exception $e) {
                $certificatePath = null;
            }
        }

        $attempt->update([
            'score' => $scorePercent,
            'certificate_path' => $certificatePath,
            'finished_at' => now(),
        ]);

        return redirect()->route('siswa.quiz-attempts.show', ['attempt' => $attempt->id])
            ->with(
                $timeExpired ? 'warning' : 'success',
                $timeExpired ? __('messages.quiz.time_expired_submitted') : __('messages.quiz.submitted')
            );
    }

    /**
     * Menampilkan hasil pengerjaan kuis secara detail.
     * Memuat semua jawaban beserta detail soal untuk review siswa.
     * Juga memuat seluruh percobaan sebelumnya pada kuis yang sama.
     */
    public function result(QuizAttempt $attempt): View
    {
        $user = Auth::user();
        abort_if($attempt->siswa_id !== $user->id, 403);

        $attempt->load(['quiz.course', 'siswa', 'answers.question']);

        $allAttempts = QuizAttempt::where('siswa_id', $user->id)
            ->where('quiz_id', $attempt->quiz_id)
            ->whereNotNull('finished_at')
            ->orderBy('created_at')
            ->get();

        return view('student.quizzes.result', compact('user', 'attempt', 'allAttempts'));
    }
}
