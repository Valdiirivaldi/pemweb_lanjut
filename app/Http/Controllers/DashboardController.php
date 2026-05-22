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
        $enrolledCourses = $user->enrolledCourses()->latest()->get();

        return view('dashboard', compact('user', 'enrolledCourses'));
    }
}
