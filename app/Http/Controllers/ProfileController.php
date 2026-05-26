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
     * Display the user's profile page.
     */
    public function show()
    {
        $user = Auth::user()->load(['siswa', 'tentor']);

        if ($user->role === 'siswa') {
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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
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
     * Delete the user's account.
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
     * Update unique_id for siswa or tentor (admin only, or self).
     */
    public function updateUniqueId(Request $request): RedirectResponse
    {
        $request->validate([
            'unique_id' => ['required', 'string', 'max:20', 'unique:siswas,unique_id', 'unique:tentors,unique_id'],
        ]);

        $user = $request->user()->load(['siswa', 'tentor']);

        if ($user->siswa) {
            $user->siswa->update(['unique_id' => $request->unique_id]);
        } elseif ($user->tentor) {
            $user->tentor->update(['unique_id' => $request->unique_id]);
        } else {
            return back()->with('error', 'Only siswa and tentor can have a unique ID.');
        }

        return Redirect::route('profile')->with('status', 'unique-id-updated');
    }
}
