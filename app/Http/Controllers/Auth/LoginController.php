<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan formulir login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan login.
     * Memanggil authenticate() pada LoginRequest untuk validasi dan attempt login.
     * Me-regenerate session ID untuk keamanan (mencegah session fixation).
     * Menyimpan data nama dan role pengguna ke flash session untuk ditampilkan di dashboard.
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        session()->flash('login-success', [
            'name' => Auth::user()->name,
            'role' => Auth::user()->role,
        ]);

        return redirect()->route('dashboard');
    }
}
