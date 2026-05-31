<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Tambahkan kolom workflow + audit trail
        Schema::table('course_user', function (Blueprint $table) {
            $table->string('status')->default('pending');
            $table->timestamp('unlocked_at')->nullable();
            $table->unsignedBigInteger('unlocked_by')->nullable();

            // 2) Foreign key unlocked_by -> users.id
            $table->foreign('unlocked_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        // 3) Backfill: sinkronkan data lama berbasis is_unlocked
        DB::table('course_user')
            ->where('is_unlocked', 1)
            ->update([
                'status' => 'active',
                'unlocked_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropForeign(['unlocked_by']);
            $table->dropColumn(['status', 'unlocked_at', 'unlocked_by']);
        });
    }
};
