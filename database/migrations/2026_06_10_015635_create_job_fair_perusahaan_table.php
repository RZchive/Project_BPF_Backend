<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_fair_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_fair_id')->constrained('job_fair')->cascadeOnDelete();
            $table->foreignId('perusahaan_id')->constrained('perusahaan_mitra')->cascadeOnDelete();
            $table->integer('jumlah_lowongan')->nullable();
            $table->integer('realisasi_penempatan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_fair_perusahaan');
    }
};