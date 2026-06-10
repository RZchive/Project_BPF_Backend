<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sertifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenaga_kerja_id')->constrained('tenaga_kerja')->cascadeOnDelete();
            $table->string('nama_sertifikasi')->nullable();
            $table->string('lembaga_sertifikasi')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('masa_berlaku')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasi');
    }
};