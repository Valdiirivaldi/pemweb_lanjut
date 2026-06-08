<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Menampilkan formulir konfirmasi password.
     * Digunakan sebelum pengguna melakukan aksi sensitif (seperti mengubah password).
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Memvalidasi konfirmasi password pengguna.
     * Jika valid, menyimpan timestamp konfirmasi ke session.
     * Jika tidak valid, melempar exception validasi dengan pesan error.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
