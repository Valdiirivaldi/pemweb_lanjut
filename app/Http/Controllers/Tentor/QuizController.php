<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
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

    public function create(): View
    {
        $user = Auth::user();
        $courses = $user->courses()->latest()->get();

        return view('tentor.quizzes.create', compact('user', 'courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'course_id'  => ['required', 'exists:courses,id'],
            'title'      => ['required', 'string', 'max:255'],
            'time_limit' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $course = Course::findOrFail($request->course_id);
        abort_if($course->tentor_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke course ini.');

        Quiz::create([
            'course_id'  => $request->course_id,
            'title'      => $request->title,
            'time_limit' => $request->time_limit,
        ]);

        return redirect()->route('tentor.quizzes.index')
            ->with('success', 'Kuis berhasil dibuat!');
    }
}
