<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, Module $module): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->enrolledCourses()->where('course_id', $module->course_id)->exists()) {
            abort(403);
        }

        $request->validate([
            'file'     => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,jpg,jpeg,png', 'max:51200'],
            'link_url' => ['nullable', 'url', 'max:500'],
            'notes'    => ['nullable', 'string', 'max:1000'],
        ]);

        if (!$request->hasFile('file') && !$request->filled('link_url')) {
            return redirect()->back()->with('error', 'Please upload a file or provide a link.');
        }

        $data = [
            'module_id'    => $module->id,
            'siswa_id'     => $user->id,
            'link_url'     => $request->link_url,
            'notes'        => $request->notes,
            'submitted_at' => now(),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('submissions', 'public');
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        ModuleSubmission::updateOrCreate(
            ['module_id' => $module->id, 'siswa_id' => $user->id],
            $data
        );

        return redirect()->back()->with('success', __('messages.submission.saved'));
    }
}
