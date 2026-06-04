<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type', 20)->default('single')->after('question_text');
            $table->json('options')->nullable()->after('type');
            $table->json('correct_options')->nullable()->after('options');
        });

        DB::statement("
            UPDATE questions
            SET
                `type` = 'single',
                `options` = JSON_OBJECT(
                    'A', option_a,
                    'B', option_b,
                    'C', option_c,
                    'D', option_d
                ),
                `correct_options` = JSON_ARRAY(correct_option)
        ");

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['option_a', 'option_b', 'option_c', 'option_d', 'correct_option']);
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('option_a')->nullable()->after('question_text');
            $table->string('option_b')->nullable()->after('option_a');
            $table->string('option_c')->nullable()->after('option_b');
            $table->string('option_d')->nullable()->after('option_c');
            $table->char('correct_option', 1)->nullable()->after('option_d');
        });

        DB::statement("
            UPDATE questions
            SET
                option_a = JSON_UNQUOTE(JSON_EXTRACT(`options`, '$.A')),
                option_b = JSON_UNQUOTE(JSON_EXTRACT(`options`, '$.B')),
                option_c = JSON_UNQUOTE(JSON_EXTRACT(`options`, '$.C')),
                option_d = JSON_UNQUOTE(JSON_EXTRACT(`options`, '$.D')),
                correct_option = JSON_UNQUOTE(JSON_EXTRACT(`correct_options`, '$[0]'))
        ");

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'options', 'correct_options']);
        });
    }
};
