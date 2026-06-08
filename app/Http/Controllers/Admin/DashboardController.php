<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan ringkasan data sistem:
     * - Total pengguna terdaftar
     * - Total kelas/kursus
     * - Total siswa yang terdaftar minimal di satu kelas
     * - 5 pengguna terbaru
     * - 5 kursus terbaru
     */
    public function index()
    {
        $user = Auth::user();

        $totalUsers    = User::count();
        $totalCourses  = Course::count();
        $totalEnrolled = User::where('role', 'siswa')
            ->whereHas('enrolledCourses')
            ->count();

        $recentUsers   = User::latest()->take(5)->get();
        $recentCourses = Course::with('tentor')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalCourses',
            'totalEnrolled',
            'recentUsers',
            'recentCourses'
        ));
    }
}
