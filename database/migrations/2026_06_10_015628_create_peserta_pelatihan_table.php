<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peserta_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenaga_kerja_id')->constrained('tenaga_kerja')->cascadeOnDelete();
            $table->foreignId('pelatihan_id')->constrained('pelatihan')->cascadeOnDelete();
            $table->enum('status_peserta', ['aktif', 'lulus', 'tidak_lulus'])->default('aktif');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_pelatihan');
    }
};