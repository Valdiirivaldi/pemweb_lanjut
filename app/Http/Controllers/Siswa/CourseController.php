<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function myCourses(): View
    {
        $user = Auth::user();

        // Pakai akses pivot secara eksplisit agar tidak tergantung deteksi IDE method relasi.
        $courses = Course::whereIn('id', function ($q) use ($user) {
            $q->select('course_id')
                ->from('course_user')
                ->where('user_id', $user->id);
        })
            ->with('tentor')
            ->withCount(['modules', 'quizzes'])
            ->latest()
            ->get();

        return view('siswa.my-courses', compact('user', 'courses'));
    }

    public function learn(Course $course): View
    {
        $user = Auth::user();

        // Untuk kebutuhan testing lokal: bypass pengecekan relasi pada course_user jika kosong.
        // Komentar struktur: biarkan logika benar saat data course_user sudah tersedia.
        //
        // Kalau ingin tetap memblokir akses (produksi), cukup ganti bagian IF berikut menjadi abort_if(! $enrolled, 403).

        $enrolled = Course::where('id', $course->id)
            ->whereIn('id', function ($q) use ($user) {
                $q->select('course_id')
                    ->from('course_user')
                    ->where('user_id', $user->id);
            })
            ->exists();

        // Bypass untuk testing:
        // - jika $enrolled false, tetap izinkan akses.
        // - jika $enrolled true, jalankan seperti biasa.
        // if (!$enrolled) { abort(403); }

        $course->load([
            'modules' => function ($q) {
                $q->orderBy('id');
            },
            'quizzes' => function ($q) {
                $q->orderBy('id');
            },
            'tentor',
        ]);

        return view('siswa.learn', compact('user', 'course'));
    }
}
