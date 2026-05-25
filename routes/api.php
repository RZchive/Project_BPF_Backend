<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TenagaKerjaController;
use App\Http\Controllers\Api\LpkController;
use App\Http\Controllers\Api\PerusahaanMitraController;
use App\Http\Controllers\Api\PelatihanController;
use App\Http\Controllers\Api\PesertaPelatihanController;
use App\Http\Controllers\Api\PemaganganganController;
use App\Http\Controllers\Api\SertifikasiController;
use App\Http\Controllers\Api\TracerStudyController;
use App\Http\Controllers\Api\JobFairController;
use App\Http\Controllers\Api\LaporanController;

// ==============================
// PUBLIC (tanpa login)
// ==============================
Route::post('/login', [AuthController::class, 'login']);

// ==============================
// PROTECTED (wajib pakai token)
// ==============================
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Master Data
    Route::apiResource('tenaga-kerja',      TenagaKerjaController::class);
    Route::apiResource('lpk',               LpkController::class);
    Route::apiResource('perusahaan-mitra',  PerusahaanMitraController::class);

    // Kegiatan
    Route::apiResource('pelatihan',         PelatihanController::class);
    Route::apiResource('peserta-pelatihan', PesertaPelatihanController::class);
    Route::apiResource('pemagangan',        PemaganganganController::class);
    Route::apiResource('sertifikasi',       SertifikasiController::class);
    Route::apiResource('tracer-study',      TracerStudyController::class);
    Route::apiResource('job-fair',          JobFairController::class);

    // Laporan
    Route::apiResource('laporan',           LaporanController::class);
});