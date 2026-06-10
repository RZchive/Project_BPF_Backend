<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpk_id')->nullable()->constrained('lpk')->cascadeOnDelete();
            $table->string('nama_pelatihan')->nullable();
            $table->string('jenis_pelatihan')->nullable();
            $table->string('jurusan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('kuota')->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};