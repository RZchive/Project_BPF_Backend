<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LpkController;
use App\Http\Controllers\Api\PerusahaanMitraController;
use App\Http\Controllers\Api\TenagaKerjaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SertifikasiController;

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

    Route::apiResource(
        'lpk',
        LpkController::class
    );

    // ======================
    // TENAGA KERJA
    // ======================

    Route::apiResource(
        'tenaga-kerja',
        TenagaKerjaController::class
    );
    Route::apiResource(
        'perusahaan-mitra',
        PerusahaanMitraController::class
    );
    Route::apiResource(
    'sertifikasi',
    SertifikasiController::class
);
});
