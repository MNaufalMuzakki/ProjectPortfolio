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
            $table->string('title'); // Contoh: "Sistem Informasi Penjualan"
            $table->string('category'); // Contoh: "Web Development" atau "Game Development"
            $table->text('description');
            
            $table->string('image_path')->nullable(); // Path foto yang di-upload ke Storage
            $table->string('url')->nullable(); // Link ke Google Drive portfolio
            
            $table->json('tech_stack')->nullable(); // JSON Array ["Laravel", "PHP", "Tailwind"]
            
            $table->timestamps();
            $table->softDeletes();
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
