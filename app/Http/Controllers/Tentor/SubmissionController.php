<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ModuleSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubmissionController extends Controller
{
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

    public function show(Module $module, ModuleSubmission $submission): View
    {
        $user = Auth::user();

        if ($module->course->tentor_id !== $user->id) {
            abort(403);
        }

        return view('tentor.modules.submissions.show', compact('module', 'submission'));
    }
}
