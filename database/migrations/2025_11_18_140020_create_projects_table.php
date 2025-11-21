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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->text('long_description')->nullable();
            $table->enum('type', ['website', 'mobile_app', 'api', 'design', 'other'])->default('website');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('context', ['academic', 'internship', 'freelance', 'personal', 'professional']);
            $table->date('completion_date');
            $table->string('client')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->string('role');
            $table->string('main_image')->nullable();
            $table->string('github_link')->nullable();
            $table->string('site_link')->nullable();
            $table->integer('display_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
