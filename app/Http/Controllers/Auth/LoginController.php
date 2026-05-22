<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            $user = Auth::user();
            $redirectUrl = match ($user->role) {
                'admin'  => '/admin/dashboard',
                'tentor' => '/tentor/dashboard',
                default  => '/dashboard',
            };

            return response()->json([
                'success'      => true,
                'message'      => 'Login berhasil!',
                'redirect_url' => $redirectUrl,
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ]);
    }
}
