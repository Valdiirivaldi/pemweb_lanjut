<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard')->with('login-success', session('login-success'));
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('login-success', session('login-success'));
        }

        if ($user->role === 'tentor') {
            return redirect()->route('tentor.dashboard')->with('login-success', session('login-success'));
        }

        return view('dashboard', compact('user'));
    }
}
