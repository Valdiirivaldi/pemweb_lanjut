<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrolledCourses = $user->enrolledCourses()
            ->with('tentor')
            ->latest()
            ->take(6)
            ->get();

        $quizAttempts = $user->quizAttempts()
            ->with('quiz.course')
            ->latest()
            ->take(5)
            ->get();

        $certificates = $user->quizAttempts()
            ->whereNotNull('certificate_path')
            ->with('quiz.course')
            ->latest()
            ->take(5)
            ->get();

        $totalEnrolled = $user->enrolledCourses()->count();
        $totalQuizzes = $user->quizAttempts()->count();
        $totalCertificates = $user->quizAttempts()->whereNotNull('certificate_path')->count();

        return view('student.dashboard', compact(
            'user',
            'enrolledCourses',
            'quizAttempts',
            'certificates',
            'totalEnrolled',
            'totalQuizzes',
            'totalCertificates'
        ));
    }
}
