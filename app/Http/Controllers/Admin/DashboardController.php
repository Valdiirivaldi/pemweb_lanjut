<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalTentors = User::where('role', 'tentor')->count();
        $totalStudents = User::where('role', 'siswa')->count();
        $totalCourses = Course::count();
        $recentUsers = User::latest()->take(5)->get();
        $recentCourses = Course::with('tentor')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'user',
            'totalAdmins',
            'totalTentors',
            'totalStudents',
            'totalCourses',
            'recentUsers',
            'recentCourses'
        ));
    }
}
