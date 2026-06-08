<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    // Course Access
    // -------------------------------------------------------

    /**
     * Cek apakah user punya akses untuk membuka konten course.
     * Akses dianggap valid jika:
     * - user terdaftar (enrolled) pada course
     * - pivot `course_user.is_unlocked` bernilai true
     */
    public function canAccessCourse(Course $course): bool
    {
        return $this->enrolledCourses()
            ->where('course_user.course_id', $course->id)
            ->wherePivot('is_unlocked', true)
            ->exists();
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
            ->withPivot(['is_unlocked', 'status', 'unlocked_at', 'unlocked_by'])
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

    /**
     * Semua pengumpulan tugas modul yang dilakukan oleh Siswa ini.
     * (User sebagai Siswa) → hasMany ModuleSubmission
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ModuleSubmission::class, 'siswa_id');
    }

    /**
     * Profil Siswa (1:1 ke tabel siswas).
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    /**
     * Profil Tentor (1:1 ke tabel tentors).
     */
    public function tentor(): HasOne
    {
        return $this->hasOne(Tentor::class, 'user_id');
    }

    /**
     * Mendapatkan unique_id berdasarkan role.
     */
    public function getUniqueIdAttribute(): ?string
    {
        return $this->siswa?->unique_id ?? $this->tentor?->unique_id;
    }
}
