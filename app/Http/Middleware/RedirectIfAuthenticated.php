<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Mengarahkan pengguna yang sudah login ke dashboard sesuai role mereka.
     * Digunakan pada halaman login dan registrasi agar pengguna yang sudah login
     * tidak perlu melihat halaman login lagi.
     * Admin → /admin/dashboard
     * Tentor → /tentor/dashboard
     * Siswa → /dashboard
     *
     * @param  Request  $request Request HTTP yang masuk
     * @param  Closure  $next    Fungsi middleware selanjutnya
     * @param  string   ...$guards Guard autentikasi yang akan diperiksa
     * @return Response          Response HTTP
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $redirectUrl = match (true) {
                    $user->isAdmin()  => route('admin.dashboard'),
                    $user->isTentor() => route('tentor.dashboard'),
                    default           => route('dashboard'),
                };
                return redirect($redirectUrl);
            }
        }

        return $next($request);
    }
}
