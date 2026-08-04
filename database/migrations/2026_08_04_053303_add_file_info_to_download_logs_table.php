<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('download_logs', function (Blueprint $table) {
            $table->string('file_name')->nullable()->after('model_id');
            $table->string('file_type')->nullable()->after('file_name');
            $table->unique(['user_id', 'model', 'model_id', 'file_name']);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'model', 'model_id', 'file_name']);
            $table->dropColumn(['file_name', 'file_type']);
        });
    }
};
