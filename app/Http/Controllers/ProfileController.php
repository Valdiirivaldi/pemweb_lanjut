<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna beserta statistik pribadi.
     * Untuk siswa, menampilkan jumlah kelas yang diikuti, sertifikat, dan kuis.
     */
    public function show()
    {
        $user = Auth::user()->load(['siswa', 'tentor']);

        if ($user->isSiswa()) {
            $totalClasses = $user->enrolledCourses()->count();
            $totalCertificates = $user->quizAttempts()->whereNotNull('certificate_path')->count();
            $totalQuizzes = $user->quizAttempts()->count();
        } else {
            $totalClasses = 0;
            $totalCertificates = 0;
            $totalQuizzes = 0;
        }

        return view('profile.show', compact('user', 'totalClasses', 'totalCertificates', 'totalQuizzes'));
    }

    /**
     * Menampilkan form untuk mengubah informasi profil (nama dan email).
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     * Jika email berubah, status verifikasi email direset (email_verified_at = null).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna secara permanen.
     * Memvalidasi password sebelum menghapus, lalu logout dan invalidate session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Memperbarui unique_id (ID unik) untuk profil siswa atau tentor.
     * Hanya bisa diakses oleh admin atau pengguna itu sendiri.
     */
    public function updateUniqueId(Request $request): RedirectResponse
    {
        $user = $request->user()->load(['siswa', 'tentor']);

        $request->validate([
            'unique_id' => [
                'required', 'string', 'max:20',
                'unique:siswas,unique_id' . ($user->siswa ? ',' . $user->siswa->id : ''),
                'unique:tentors,unique_id' . ($user->tentor ? ',' . $user->tentor->id : ''),
            ],
        ]);

        if ($user->siswa) {
            $user->siswa->update(['unique_id' => $request->unique_id]);
        } elseif ($user->tentor) {
            $user->tentor->update(['unique_id' => $request->unique_id]);
        } else {
            return back()->with('error', __('messages.error.only_siswa_tentor'));
        }

        return Redirect::route('profile')->with('status', 'unique-id-updated');
    }
}
