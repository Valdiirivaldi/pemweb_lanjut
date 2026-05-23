<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $courseIds = $user->courses()->pluck('id');
        $modules = Module::whereIn('course_id', $courseIds)
            ->with('course')
            ->latest()
            ->get();

        return view('tentor.modules.index', compact('user', 'modules'));
    }
}
