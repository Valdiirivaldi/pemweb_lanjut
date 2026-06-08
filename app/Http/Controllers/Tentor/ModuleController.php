<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ModuleController extends Controller
{
    /**
     * Menampilkan daftar semua modul di seluruh kelas milik tentor ini.
     * Setiap modul dilengkapi dengan jumlah file lampiran.
     */
    public function index(): View
    {
        $user = Auth::user();
        $courseIds = $user->courses()->pluck('id');
        $modules = Module::whereIn('course_id', $courseIds)
            ->with('course')
            ->withCount('files')
            ->latest()
            ->get();

        return view('tentor.modules.index', compact('user', 'modules'));
    }

    /**
     * Menampilkan formulir untuk membuat modul baru.
     * Menerima parameter query course_id untuk pra-milih kelas tertentu.
     * Membatalkan aksi jika kelas bukan milik tentor ini (403).
     */
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

    /**
     * Menyimpan modul baru ke database beserta file-file lampiran.
     * File disimpan di folder 'modules' pada disk public.
     * Memvalidasi bahwa kelas yang dipilih adalah milik tentor ini.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title'     => ['required', 'string', 'max:255'],
            'content'   => ['nullable', 'string', 'max:50000'],
            'video_url' => ['nullable', 'string', 'max:500'],
            'link_url'  => ['nullable', 'url', 'max:500'],
            'files'     => ['nullable', 'array'],
            'files.*'   => ['file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,jpg,jpeg,png,gif,mp4,webm', 'max:102400'],
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($course->tentor_id !== $user->id) {
            abort(403);
        }

        $data = array_filter($request->only(['course_id', 'title', 'content', 'video_url', 'link_url']));

        $module = Module::create($data);

        if ($request->hasFile('files')) {
            try {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('modules', 'public');
                    $module->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->route('tentor.courses.show', $course->id)
                    ->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
            }
        }

        return redirect()->route('tentor.courses.show', $course->id)
            ->with('success', __('messages.module.created'));
    }

    /**
     * Menampilkan formulir untuk mengedit modul yang sudah ada.
     * Hanya bisa diakses oleh tentor pemilik modul (403 jika bukan pemilik).
     */
    public function edit(Module $module): View
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        $courses = $user->courses()->latest()->get();

        return view('tentor.modules.edit', compact('user', 'module', 'courses'));
    }

    /**
     * Memperbarui modul yang sudah ada di database.
     * Jika ada file baru yang diunggah, file tersebut ditambahkan ke modul.
     */
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
            'files'     => ['nullable', 'array'],
            'files.*'   => ['file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar,jpg,jpeg,png,gif,mp4,webm', 'max:102400'],
        ]);

        $data = array_filter($request->only(['course_id', 'title', 'content', 'video_url', 'link_url']));

        $module->update($data);

        if ($request->hasFile('files')) {
            try {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('modules', 'public');
                    $module->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_size' => $file->getSize(),
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->route('tentor.courses.show', $module->course_id)
                    ->with('error', 'Gagal mengunggah file. Silakan coba lagi.');
            }
        }

        return redirect()->route('tentor.courses.show', $module->course_id)
            ->with('success', __('messages.module.updated'));
    }

    /**
     * Menghapus satu file lampiran dari modul tertentu.
     * File dihapus dari storage fisik dan dari database.
     */
    public function destroyFile(Module $module, ModuleFile $file): RedirectResponse
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        try {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        } catch (\Exception $e) {
            return redirect()->route('tentor.courses.show', $module->course_id)
                ->with('error', 'Gagal menghapus file. Silakan coba lagi.');
        }

        return redirect()->route('tentor.courses.show', $module->course_id)
            ->with('success', __('messages.module.file_deleted'));
    }

    /**
     * Menghapus modul secara permanen beserta semua file lampirannya.
     * Semua file terkait dihapus dari storage fisik sebelum modul dihapus dari database.
     */
    public function destroy(Module $module): RedirectResponse
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        $courseId = $module->course_id;

        try {
            foreach ($module->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }

            if ($module->pdf_path) {
                Storage::disk('public')->delete($module->pdf_path);
            }

            $module->delete();
        } catch (\Exception $e) {
            return redirect()->route('tentor.courses.show', $courseId)
                ->with('error', 'Gagal menghapus modul. Silakan coba lagi.');
        }

        return redirect()->route('tentor.courses.show', $courseId)
            ->with('success', __('messages.module.deleted'));
    }
}
