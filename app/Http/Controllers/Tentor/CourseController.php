<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $courses = $user->courses()->withCount('modules', 'quizzes', 'students')->latest()->get();

        return view('tentor.courses.index', compact('user', 'courses'));
    }

    public function create(): View
    {
        return view('tentor.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);

        Course::create([
            'title'       => $request->title,
            'description' => $request->description,
            'tentor_id'   => Auth::id(),
        ]);

        return redirect()->route('tentor.courses.index')
            ->with('success', __('messages.course.created'));
    }

    public function show(Course $course): View
    {
        $user = Auth::user();

        if ($course->tentor_id !== $user->id) {
            abort(403);
        }

        $course->load(['modules' => function ($q) {
            $q->withCount('submissions')->latest();
        }]);

        return view('tentor.courses.show', compact('user', 'course'));
    }
}
