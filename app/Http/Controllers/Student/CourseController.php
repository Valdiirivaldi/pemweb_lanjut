<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    /**
     * Menampilkan daftar kelas untuk siswa:
     * - myCourses: kelas yang sudah diikuti (enrolled)
     * - allCourses: semua kelas yang tersedia
     * Mendukung pencarian berdasarkan judul kelas via parameter ?search=.
     * Menandai kelas mana yang sudah diikuti siswa.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->search;

        $myCourses = $user->enrolledCourses()
            ->with('tentor')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        $allCourses = Course::with('tentor')
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        $enrolledIds = $myCourses->pluck('id')->toArray();

        return view('student.courses.index', compact('myCourses', 'allCourses', 'enrolledIds', 'search'));
    }

    /**
     * Mendaftarkan siswa ke sebuah kelas/kursus (self-enrollment).
     * Status awal: pending, kelas belum terbuka (is_unlocked = 0).
     * Admin harus membuka akses kelas sebelum siswa bisa mengakses konten.
     * Mencegah pendaftaran ganda (sama siswa di kelas yang sama).
     */
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $courseId = $request->course_id;

        if ($user->enrolledCourses()->where('course_id', $courseId)->exists()) {
            return redirect()->back()->with('error', __('messages.enroll.already'));
        }

        $user->enrolledCourses()->attach($courseId, [
            'is_unlocked' => 0,
            'status' => 'pending',
            'unlocked_at' => null,
            'unlocked_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $courseTitle = Course::findOrFail($courseId)->title;


        return redirect()->route('siswa.courses.index')
            ->with('success', __('messages.enroll.success', ['course' => $courseTitle]));
    }

    /**
     * Menampilkan halaman pembelajaran untuk satu kelas/kursus.
     * Memuat semua modul (dengan submission dan file), tentor, kuis (dengan soal dan percobaan siswa).
     * Hanya bisa diakses oleh siswa yang terdaftar di kelas tersebut.
     */
    public function learn(Course $course)
    {
        $user = Auth::user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $course->load(['modules.submissions', 'modules.files', 'tentor', 'quizzes.questions', 'quizzes.attempts' => function ($q) use ($user) {
            $q->where('siswa_id', $user->id);
        }]);

        return view('student.courses.learn', compact('user', 'course'));
    }
}
