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
        // 1. Pembuatan Akun Admin Utama
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Pembuatan Akun Tentor/Pengajar Contoh
        User::create([
            'name' => 'Pengajar Lionel',
            'email' => 'tentor@lms.com',
            'password' => Hash::make('password123'),
            'role' => 'tentor',
        ]);

        // 3. Pembuatan Akun Siswa Contoh
        User::create([
            'name' => 'Siswa Budi',
            'email' => 'siswa@lms.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);
    }
}