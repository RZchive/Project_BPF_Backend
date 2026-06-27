<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sertifikasi', function (Blueprint $table) {
            $table->string('file_sertifikat')->nullable()->after('masa_berlaku');
            $table->enum('status_sertifikat', ['aktif', 'tidak_aktif'])->default('aktif')->after('file_sertifikat');
        });
    }

    public function down(): void
    {
        Schema::table('sertifikasi', function (Blueprint $table) {
            $table->dropColumn(['file_sertifikat', 'status_sertifikat']);
        });
    }
};
