<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCourseAccessible
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda harus login terlebih dahulu.');
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

        abort_if(!$course, 404);

        // Validasi akses berbasis pivot course_user.is_unlocked
        if (!$user->canAccessCourse($course)) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Akses ditolak: Kursus ini masih dikunci oleh admin.');
        }

        return $next($request);
    }
}
