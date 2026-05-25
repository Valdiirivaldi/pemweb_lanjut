<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
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

        $enrolledIds = $user->enrolledCourses()->pluck('course_id')->toArray();

        return view('student.courses.index', compact('myCourses', 'allCourses', 'enrolledIds', 'search'));
    }

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

        $user->enrolledCourses()->attach($courseId);

        $courseTitle = Course::find($courseId)->title;

        return redirect()->route('siswa.courses.index')
            ->with('success', __('messages.enroll.success', ['course' => $courseTitle]));
    }

    public function learn(Course $course)
    {
        $user = Auth::user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $course->load(['modules', 'tentor']);

        return view('student.courses.learn', compact('user', 'course'));
    }
}