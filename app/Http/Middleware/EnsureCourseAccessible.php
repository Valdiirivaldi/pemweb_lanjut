<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseAccessible
{
    /**
     * Memastikan pengguna memiliki akses ke halaman pembelajaran kelas/kursus tertentu.
     * Memvalidasi bahwa:
     * 1. Pengguna sudah login
     * 2. Kursus ditemukan (dari model binding atau ID)
     * 3. Pengguna terdaftar di kursus tersebut DAN akses sudah dibuka oleh admin
     *    (berdasarkan kolom is_unlocked pada pivot course_user)
     * Jika validasi gagal, pengguna diarahkan ke dashboard dengan pesan error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login')
                ->with('error', __('messages.error.session_expired'));
        }

        // Idealnya route param bernama {course}

        $courseParam = $request->route('course');

        /** @var Course|null $course */
        $course = null;

        // Jika route param sudah berupa model binding
        if ($courseParam instanceof Course) {
            $course = $courseParam;
        }

        // Jika route param berupa ID
        if (!$course && $courseParam) {
            $course = Course::find($courseParam);
        }

        // Fallback: beberapa developer kadang pakai param lain
        if (!$course) {
            $idParam = $request->route('id') ?? $request->route('courseId');
            if ($idParam) {
                $course = Course::find($idParam);
            }
        }

        // Fallback: route quiz memakai param {quiz}
        if (!$course) {
            $quizParam = $request->route('quiz');
            if ($quizParam instanceof Quiz) {
                $course = $quizParam->course;
            } elseif ($quizParam) {
                $course = Quiz::find($quizParam)?->course;
            }
        }

        // Fallback: route quiz-attempts memakai param {attempt}
        if (!$course) {
            $attemptParam = $request->route('attempt');
            if ($attemptParam instanceof QuizAttempt) {
                $course = $attemptParam->quiz->course;
            } elseif ($attemptParam) {
                $course = QuizAttempt::find($attemptParam)?->quiz?->course;
            }
        }

        abort_if(!$course, 404);

        // Validasi akses berbasis pivot course_user.is_unlocked
        if (!$user->canAccessCourse($course)) {
            return redirect()->route('siswa.dashboard')
                ->with('error', __('messages.error.access_denied'));
        }

        return $next($request);
    }
}
