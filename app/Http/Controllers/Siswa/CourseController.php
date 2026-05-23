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

        $enrolled = Course::where('id', $course->id)
            ->whereIn('id', function ($q) use ($user) {
                $q->select('course_id')
                    ->from('course_user')
                    ->where('user_id', $user->id);
            })
            ->exists();

        abort_if(!$enrolled, 403);


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
