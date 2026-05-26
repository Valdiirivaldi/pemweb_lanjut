<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $courseIds = $user->courses()->pluck('id');
        $modules = Module::whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->get();

        return view('tentor.modules.index', compact('user', 'modules'));
    }

    public function create(Request $request): View
    {
        $user = Auth::user();
        $courses = $user->courses()->latest()->get();
        $selectedCourseId = $request->query('course_id');

        if ($selectedCourseId && !$courses->pluck('id')->contains($selectedCourseId)) {
            abort(403);
        }

        return view('tentor.modules.create', compact('user', 'courses', 'selectedCourseId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title'     => ['required', 'string', 'max:255'],
            'content'   => ['nullable', 'string', 'max:50000'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'link_url'  => ['nullable', 'url', 'max:500'],
            'file'      => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar', 'max:102400'],
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($course->tentor_id !== $user->id) {
            abort(403);
        }

        $data = $request->only(['course_id', 'title', 'content', 'video_url', 'link_url']);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('modules', 'public');
            $data['pdf_path'] = $path;
        }

        Module::create($data);

        return redirect()->route('tentor.courses.show', $course->id)
            ->with('success', __('messages.module.created'));
    }

    public function edit(Module $module): View
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        $courses = $user->courses()->latest()->get();

        return view('tentor.modules.edit', compact('user', 'module', 'courses'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title'     => ['required', 'string', 'max:255'],
            'content'   => ['nullable', 'string', 'max:50000'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'link_url'  => ['nullable', 'url', 'max:500'],
            'file'      => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar', 'max:102400'],
        ]);

        $data = $request->only(['course_id', 'title', 'content', 'video_url', 'link_url']);

        if ($request->hasFile('file')) {
            if ($module->pdf_path) {
                Storage::disk('public')->delete($module->pdf_path);
            }
            $path = $request->file('file')->store('modules', 'public');
            $data['pdf_path'] = $path;
        }

        $module->update($data);

        return redirect()->route('tentor.courses.show', $module->course_id)
            ->with('success', __('messages.module.updated'));
    }

    public function destroy(Module $module): RedirectResponse
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        $courseId = $module->course_id;

        if ($module->pdf_path) {
            Storage::disk('public')->delete($module->pdf_path);
        }

        $module->delete();

        return redirect()->route('tentor.courses.show', $courseId)
            ->with('success', __('messages.module.deleted'));
    }
}
