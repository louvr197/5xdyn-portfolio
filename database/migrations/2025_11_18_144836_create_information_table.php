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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('professional_title');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->text('availability')->nullable();
            $table->text('bio')->nullable();
            $table->string('picture_path')->nullable();
            $table->string('cv_path')->nullable();
            $table->json('social_links')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};
