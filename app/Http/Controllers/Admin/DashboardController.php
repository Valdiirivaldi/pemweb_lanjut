<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan ringkasan data sistem:
     * - Total pengguna terdaftar
     * - Total kelas/kursus
     * - Total siswa yang terdaftar minimal di satu kelas
     * - 5 pengguna terbaru
     * - 5 kursus terbaru
     * - Grafik pertumbuhan pengguna per bulan
     * - Distribusi role pengguna
     * - Status enrollment
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

        // Chart: user growth per month
        $userGrowth = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Chart: role distribution
        $roleDistribution = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        // Chart: enrollment status breakdown
        $enrollmentStatus = DB::table('course_user')
            ->selectRaw("COALESCE(status, 'pending') as status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalCourses',
            'totalEnrolled',
            'recentUsers',
            'recentCourses',
            'userGrowth',
            'roleDistribution',
            'enrollmentStatus'
        ));
    }
}
