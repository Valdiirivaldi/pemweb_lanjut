<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // -------------------------------------------------------
    // Role Helper Methods
    // -------------------------------------------------------

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTentor(): bool
    {
        return $this->role === 'tentor';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    /**
     * Kelas-kelas yang dipegang oleh Tentor ini.
     * (User sebagai Tentor) → hasMany Course
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'tentor_id');
    }

    /**
     * Kelas yang diikuti oleh Siswa (via tabel pivot course_user).
     * (User sebagai Siswa) → belongsToMany Course
     */
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')
                    ->withTimestamps();
    }

    /**
     * Semua percobaan kuis yang dilakukan oleh Siswa ini.
     * (User sebagai Siswa) → hasMany QuizAttempt
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'siswa_id');
    }
}
