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
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'education' atau 'certification'
            $table->string('title'); // Contoh: "Telkom University" atau "BNSP Competency Certificate"
            $table->string('subtitle'); // Contoh: "July 2023 - Present" atau "Desainer Multimedia Madya"
            $table->text('description')->nullable(); // Deskripsi lengkap
            
            // Kita pakai tipe JSON untuk menampung skor/link sertifikat yang beda-beda tiap data
            // Contoh isi: {"GPA": "3.95", "EPRT": "537"}
            $table->json('metrics')->nullable(); 
            
            $table->string('certificate_link')->nullable(); // Link Google Drive
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
