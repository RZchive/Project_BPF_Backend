<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('peserta_pelatihan', function (Blueprint $table) {
            $table->integer('nilai')->nullable()->after('status_peserta');
            $table->string('foto')->nullable()->after('nilai');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_pelatihan', function (Blueprint $table) {
            $table->dropColumn(['nilai', 'foto']);
        });
    }
};
