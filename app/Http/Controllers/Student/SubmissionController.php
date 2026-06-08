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
    /**
     * Mengumpulkan tugas untuk modul tertentu.
     * Siswa bisa mengunggah file (pdf, doc, ppt, dll. max 50MB) atau mengirimkan link URL.
     * Menggunakan updateOrCreate untuk memungkinkan pengumpulan ulang (re-submission).
     * Waktu pengumpulan (submitted_at) dicatat otomatis.
     */
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
            return redirect()->back()->with('error', __('messages.error.upload_required'));
        }

        $data = [
            'module_id'    => $module->id,
            'siswa_id'     => $user->id,
            'link_url'     => $request->link_url,
            'notes'        => $request->notes,
            'submitted_at' => now(),
        ];

        if ($request->hasFile('file')) {
            try {
                $file = $request->file('file');
                $path = $file->store('submissions', 'public');
                $data['file_path'] = $path;
                $data['file_name'] = $file->getClientOriginalName();
                $data['file_size'] = $file->getSize();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
            }
        }

        try {
            ModuleSubmission::updateOrCreate(
                ['module_id' => $module->id, 'siswa_id' => $user->id],
                $data
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pengumpulan tugas. Silakan coba lagi.');
        }

        return redirect()->back()->with('success', __('messages.submission.saved'));
    }
}
