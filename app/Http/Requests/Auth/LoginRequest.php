<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan melakukan request ini.
     * Selalu mengembalikan true karena login adalah aksi publik.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk form login.
     * Email harus valid, password wajib diisi.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Mencoba melakukan autentikasi dengan kredensial yang diberikan.
     * Memanggil ensureIsNotRateLimited() terlebih dahulu untuk memeriksa batas percobaan.
     * Jika autentikasi gagal, menambah hit counter pada rate limiter dan melempar exception.
     * Jika berhasil, membersihkan hit counter rate limiter.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Memastikan permintaan login tidak melebihi batas percobaan (rate limiting).
     * Maksimal 5 percobaan login gagal. Jika terlalu banyak percobaan,
     * pengguna harus menunggu beberapa detik sebelum bisa mencoba lagi.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Mendapatkan kunci unik untuk rate limiter berdasarkan email dan alamat IP.
     * Digunakan untuk melacak percobaan login per kombinasi email + IP.
     *
     * @return string  Kunci throttle dalam format "email|ip"
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
