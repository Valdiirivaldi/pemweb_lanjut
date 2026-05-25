<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        }

        $enrolledCourses = $user->enrolledCourses()->latest()->get();

        $quizAttempts = $user->quizAttempts()
            ->with('quiz.course')
            ->latest()
            ->get();

        $certificates = $user->quizAttempts()
            ->whereNotNull('certificate_path')
            ->with('quiz.course')
            ->latest()
            ->get();

        return view('dashboard', compact('user', 'enrolledCourses', 'quizAttempts', 'certificates'));
    }
}
