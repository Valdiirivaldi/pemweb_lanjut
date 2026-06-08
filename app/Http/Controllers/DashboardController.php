<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard berdasarkan peran (role) masing-masing.
     * Siswa → /siswa/dashboard
     * Admin → /admin/dashboard
     * Tentor → /tentor/dashboard
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSiswa()) {
            return redirect()->route('siswa.dashboard')->with('login-success', session('login-success'));
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('login-success', session('login-success'));
        }

        if ($user->isTentor()) {
            return redirect()->route('tentor.dashboard')->with('login-success', session('login-success'));
        }

        return view('dashboard', compact('user'));
    }
}
