<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $redirectUrl = match ($user->role) {
                'admin'  => '/admin/dashboard',
                'tentor' => '/tentor/dashboard',
                default  => '/dashboard',
            };
            return redirect($redirectUrl);
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'siswa',
            ]);

            Siswa::create([
                'user_id'   => $user->id,
                'unique_id' => $this->generateSiswaId(),
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect('/dashboard');
    }

    private function generateSiswaId(): string
    {
        $year = date('Y');
        $last = Siswa::where('unique_id', 'like', "S-{$year}-%")
            ->orderBy('unique_id', 'desc')
            ->lockForUpdate()
            ->value('unique_id');

        if ($last) {
            $num = (int) substr($last, -4) + 1;
        } else {
            $num = 1;
        }

        return 'S-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
