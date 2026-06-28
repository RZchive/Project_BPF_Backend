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
        Schema::create('sertifikasi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('peserta_pelatihan_id')
                ->constrained('peserta_pelatihan')
                ->cascadeOnDelete();

            $table->string('nama_sertifikasi');

            $table->string('lembaga_sertifikasi');

            $table->string('nomor_sertifikat')->unique();

            $table->date('tanggal_terbit');

            $table->date('masa_berlaku')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikasi');
    }
};