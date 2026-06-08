<?php

namespace App\Http\Controllers\Tentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard tentor dengan data:
     * - Daftar kelas yang diampu beserta jumlah siswa, modul, dan kuis
     * - Total seluruh siswa di semua kelas
     * - Total seluruh kuis di semua kelas
     */
    public function index()
    {
        $user = Auth::user();
        $courses = $user->courses()->withCount('students', 'modules', 'quizzes')->latest()->get();
        $totalStudents = $courses->sum('students_count');
        $totalQuizzes = Quiz::whereIn('course_id', $courses->pluck('id'))->count();

        return view('tentor.dashboard', compact(
            'user',
            'courses',
            'totalStudents',
            'totalQuizzes'
        ));
    }
}
