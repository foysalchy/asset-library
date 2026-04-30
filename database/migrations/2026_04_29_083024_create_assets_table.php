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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_type_id')->constrained('asset_types');
            $table->string('title');
            $table->string('asset_id_code')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->unique();

            // Download file
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->bigInteger('file_size')->nullable();

            // Meta
            $table->json('available_formats')->nullable();
            $table->json('dimensions')->nullable();
            $table->integer('sort_order')->default(0);
            $table->date('uploaded_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
