<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LpkController;
use App\Http\Controllers\Api\PerusahaanMitraController;
use App\Http\Controllers\Api\TenagaKerjaController;
use App\Http\Controllers\Api\SertifikasiController;
use App\Http\Controllers\Api\JobFairController;
use App\Http\Controllers\Api\JobFairPerusahaanController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\PelatihanController;
use App\Http\Controllers\Api\PemaganganController;
use App\Http\Controllers\Api\PesertaPelatihanController;
use App\Http\Controllers\Api\TracerStudyController;
use Illuminate\Support\Facades\Route;

// ======================================
// PUBLIC ROUTES
// ======================================

Route::post('/login', [AuthController::class, 'login']);

// ======================================
// PROTECTED ROUTES (SANCTUM)
// ======================================

Route::middleware('auth:sanctum')->group(function () {

    // ======================
    // AUTH
    // ======================
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ======================
    // LPK
    // ======================
    Route::apiResource('lpk', LpkController::class);

    // ======================
    // TENAGA KERJA
    // ======================
    Route::apiResource('tenaga-kerja', TenagaKerjaController::class);

    // ======================
    // PERUSAHAAN MITRA
    // ======================
    Route::apiResource('perusahaan-mitra', PerusahaanMitraController::class);

    // ======================
    // SERTIFIKASI
    // ======================
    Route::apiResource('sertifikasi', SertifikasiController::class);

    // ======================
    // JOB FAIR
    // ======================
    Route::apiResource('job-fair', JobFairController::class);

    // ======================
    // JOB FAIR PERUSAHAAN
    // ======================
    Route::apiResource('job-fair-perusahaan', JobFairPerusahaanController::class);

    // ======================
    // LAPORAN
    // ======================
    Route::apiResource('laporan', LaporanController::class);

    // ======================
    // PELATIHAN
    // ======================
    Route::apiResource('pelatihan', PelatihanController::class);

    // ======================
    // PEMAGANGAN
    // ======================
    Route::apiResource('pemagangan', PemaganganController::class);

    // ======================
    // PESERTA PELATIHAN
    // ======================
    Route::apiResource('peserta-pelatihan', PesertaPelatihanController::class);

    // ======================
    // TRACER STUDY
    // ======================
    Route::apiResource('tracer-study', TracerStudyController::class);
});

