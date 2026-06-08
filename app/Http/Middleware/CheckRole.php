<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Memeriksa apakah pengguna yang sedang login memiliki role yang diizinkan.
     * Digunakan dalam route untuk membatasi akses berdasarkan peran (role).
     * Contoh penggunaan: middleware('role:admin,tentor')
     *
     * @param  Request   $request  Request HTTP yang masuk
     * @param  Closure   $next     Fungsi middleware selanjutnya
     * @param  string    ...$roles Daftar role yang diizinkan (admin, tentor, siswa)
     * @return Response            Response HTTP
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
