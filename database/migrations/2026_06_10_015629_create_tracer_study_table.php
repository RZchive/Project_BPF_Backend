<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tracer_study', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenaga_kerja_id')->constrained('tenaga_kerja')->cascadeOnDelete();
            $table->enum('status_alumni', ['bekerja_sesuai_bidang', 'membuka_usaha', 'belum_bekerja'])->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('gaji')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal_update')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_study');
    }
};