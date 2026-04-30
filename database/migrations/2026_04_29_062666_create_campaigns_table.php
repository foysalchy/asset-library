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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->bigInteger('project_id')->unsigned()->nullable();
            $table->longText('description')->nullable();
            $table->enum('status', ['draft', 'active', 'expired'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('thumbnail')->nullable();
            $table->string('file')->nullable();
            $table->json('languages'); // ['en', 'bn']
            $table->integer('sort_order')->default(0);
            $table->foreignUuid('created_by')->nullable();
            $table->date('published_at')->nullable();
            $table->date('expired_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
