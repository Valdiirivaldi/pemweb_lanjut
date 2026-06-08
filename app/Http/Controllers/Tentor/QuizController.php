<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    /**
     * Menampilkan daftar semua kuis di seluruh kelas milik tentor ini.
     * Setiap kuis dilengkapi dengan jumlah soal dan jumlah percobaan pengerjaan siswa.
     */
    public function index(): View
    {
        $user = Auth::user();
        $courseIds = $user->courses()->pluck('id');
        $quizzes = Quiz::whereIn('course_id', $courseIds)
            ->with('course')
            ->withCount('questions', 'attempts')
            ->latest()
            ->get();

        return view('tentor.quizzes.index', compact('user', 'quizzes'));
    }

    /**
     * Menampilkan formulir untuk membuat kuis baru.
     */
    public function create(): View
    {
        $user = Auth::user();
        $courses = $user->courses()->latest()->get();

        return view('tentor.quizzes.create', compact('user', 'courses'));
    }

    /**
     * Menyimpan kuis baru ke database.
     * Memvalidasi bahwa kelas yang dipilih adalah milik tentor ini.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'course_id'      => ['required', 'exists:courses,id'],
            'title'          => ['required', 'string', 'max:255'],
            'time_limit'     => ['required', 'integer', 'min:1', 'max:999'],
            'passing_score'  => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $course = Course::findOrFail($request->course_id);
        abort_if($course->tentor_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke course ini.');

        Quiz::create([
            'course_id'      => $request->course_id,
            'title'          => $request->title,
            'time_limit'     => $request->time_limit,
            'passing_score'  => $request->passing_score,
        ]);

        return redirect()->route('tentor.quizzes.index')
            ->with('success', __('messages.quiz.created'));
    }

    /**
     * Menampilkan formulir untuk mengedit kuis yang sudah ada.
     * Membatalkan aksi jika kuis bukan milik tentor ini (403).
     */
    public function edit(Quiz $quiz): View
    {
        $user = Auth::user();
        abort_if($quiz->course->tentor_id !== $user->id, 403);

        $courses = $user->courses()->latest()->get();

        return view('tentor.quizzes.edit', compact('user', 'quiz', 'courses'));
    }

    /**
     * Memperbarui data kuis yang sudah ada.
     * Memvalidasi bahwa kelas tujuan juga adalah milik tentor ini.
     */
    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        abort_if($quiz->course->tentor_id !== Auth::id(), 403);

        $request->validate([
            'course_id'      => ['required', 'exists:courses,id'],
            'title'          => ['required', 'string', 'max:255'],
            'time_limit'     => ['required', 'integer', 'min:1', 'max:999'],
            'passing_score'  => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $course = Course::findOrFail($request->course_id);
        abort_if($course->tentor_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke course ini.');

        $quiz->update([
            'course_id'      => $request->course_id,
            'title'          => $request->title,
            'time_limit'     => $request->time_limit,
            'passing_score'  => $request->passing_score,
        ]);

        return redirect()->route('tentor.quizzes.index')
            ->with('success', __('messages.quiz.updated'));
    }

    /**
     * Menghapus kuis secara permanen dari database.
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        abort_if($quiz->course->tentor_id !== Auth::id(), 403);

        $quiz->delete();

        return redirect()->route('tentor.quizzes.index')
            ->with('success', __('messages.quiz.deleted'));
    }

    /**
     * Menampilkan daftar semua percobaan pengerjaan kuis oleh siswa.
     * Berguna untuk tentor melihat siapa saja yang sudah mengerjakan kuis ini.
     */
    public function attemptsIndex(Quiz $quiz): View
    {
        abort_if($quiz->course->tentor_id !== Auth::id(), 403);

        $attempts = $quiz->attempts()
            ->with('siswa')
            ->latest()
            ->get();

        return view('tentor.quizzes.attempts-index', compact('quiz', 'attempts'));
    }

    /**
     * Menampilkan detail satu percobaan pengerjaan kuis.
     * Memuat data siswa, semua jawaban, dan detail soal untuk review tentor.
     */
    public function attemptShow(Quiz $quiz, QuizAttempt $attempt): View
    {
        abort_if($quiz->course->tentor_id !== Auth::id(), 403);
        abort_if($attempt->quiz_id !== $quiz->id, 404);

        $attempt->load(['siswa', 'answers.question']);

        return view('tentor.quizzes.attempt-show', compact('quiz', 'attempt'));
    }
}
