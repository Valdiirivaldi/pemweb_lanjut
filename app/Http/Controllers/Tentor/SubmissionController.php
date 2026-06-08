<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    /**
     * Menampilkan daftar semua pengumpulan tugas dari siswa untuk modul tertentu.
     * Diurutkan berdasarkan waktu pengumpulan terbaru.
     * Hanya bisa diakses oleh tentor pemilik modul (403 jika bukan pemilik).
     */
    public function index(Module $module): View
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        $submissions = $module->submissions()
            ->with('siswa')
            ->latest('submitted_at')
            ->get();

        return view('tentor.modules.submissions.index', compact('module', 'submissions'));
    }

    /**
     * Menampilkan detail satu pengumpulan tugas dari siswa.
     * Berguna untuk tentor melihat file yang dikumpulkan dan catatan siswa.
     */
    public function show(Module $module, ModuleSubmission $submission): View
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        return view('tentor.modules.submissions.show', compact('module', 'submission'));
    }
}
