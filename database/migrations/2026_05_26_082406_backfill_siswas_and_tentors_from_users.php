<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $year = date('Y');

        // Backfill siswas
        $siswaUsers = DB::table('users')->where('role', 'siswa')->orderBy('id')->get();
        $siswaCount = DB::table('siswas')->count();
        $seq = $siswaCount + 1;

        foreach ($siswaUsers as $user) {
            $existing = DB::table('siswas')->where('user_id', $user->id)->first();
            if ($existing) continue;

            $uniqueId = 'S-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            DB::table('siswas')->insert([
                'user_id'    => $user->id,
                'unique_id'  => $uniqueId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $seq++;
        }

        // Backfill tentors
        $tentorUsers = DB::table('users')->where('role', 'tentor')->orderBy('id')->get();
        $tentorCount = DB::table('tentors')->count();
        $seq = $tentorCount + 1;

        foreach ($tentorUsers as $user) {
            $existing = DB::table('tentors')->where('user_id', $user->id)->first();
            if ($existing) continue;

            $uniqueId = 'T-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            DB::table('tentors')->insert([
                'user_id'    => $user->id,
                'unique_id'  => $uniqueId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $seq++;
        }
    }

    public function down(): void
    {
        // Data loss is acceptable; no reversal needed.
    }
};
