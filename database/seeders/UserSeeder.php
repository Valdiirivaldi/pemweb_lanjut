<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Tentor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin Utama',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // 2. Akun Tentor + buat record di tabel tentors
        DB::transaction(function () {
            $tentor = User::updateOrCreate(
                ['email' => 'tentor@gmail.com'],
                [
                    'name'     => 'Tentor Pengajar',
                    'password' => Hash::make('password'),
                    'role'     => 'tentor',
                ]
            );

            if (!$tentor->tentor) {
                Tentor::create([
                    'user_id'   => $tentor->id,
                    'unique_id' => $this->generateTentorId(),
                ]);
            }
        });

        // 3. Akun Siswa + buat record di tabel siswas
        DB::transaction(function () {
            $siswa = User::updateOrCreate(
                ['email' => 'siswa@gmail.com'],
                [
                    'name'     => 'Siswa Belajar',
                    'password' => Hash::make('password'),
                    'role'     => 'siswa',
                ]
            );

            if (!$siswa->siswa) {
                Siswa::create([
                    'user_id'   => $siswa->id,
                    'unique_id' => $this->generateSiswaId(),
                ]);
            }
        });
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

    private function generateTentorId(): string
    {
        $year = date('Y');
        $last = Tentor::where('unique_id', 'like', "T-{$year}-%")
            ->orderBy('unique_id', 'desc')
            ->lockForUpdate()
            ->value('unique_id');

        if ($last) {
            $num = (int) substr($last, -4) + 1;
        } else {
            $num = 1;
        }

        return 'T-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
