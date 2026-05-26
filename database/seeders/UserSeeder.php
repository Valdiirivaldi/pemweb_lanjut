<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan updateOrCreate supaya seed bisa dijalankan berulang tanpa error unique email.

        // 1. Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@lms.com'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Akun Tentor/Pengajar Contoh
        User::updateOrCreate(
            ['email' => 'tentor23@lms.com'],
            [
                'name' => 'Pengajar Lionel',
                'password' => Hash::make('password12321314135DFF'),
                'role' => 'tentor',
            ]
        );

        // 3. Akun Siswa Contoh
        User::updateOrCreate(
            ['email' => 'siswa@lms.com'],
            [
                'name' => 'Siswa Budi',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
            ]
        );
    }
}

