<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->unique('user_id', 'siswas_user_id_unique');
        });

        Schema::table('tentors', function (Blueprint $table) {
            $table->unique('user_id', 'tentors_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropUnique('siswas_user_id_unique');
        });

        Schema::table('tentors', function (Blueprint $table) {
            $table->dropUnique('tentors_user_id_unique');
        });
    }
};
