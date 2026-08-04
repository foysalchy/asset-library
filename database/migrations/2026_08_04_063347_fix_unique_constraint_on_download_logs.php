<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropUnique('download_logs_user_id_model_model_id_unique');

            $table->unique(['user_id', 'model', 'model_id', 'file_name'], 'download_logs_unique');
        });
    }

    public function down(): void
    {
        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropUnique('download_logs_unique');
            $table->unique(['user_id', 'model', 'model_id']);
        });
    }
};
