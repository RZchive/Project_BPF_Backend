<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LpkController;
use App\Http\Controllers\Api\PerusahaanMitraController;
use App\Http\Controllers\Api\TenagaKerjaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SertifikasiController;
use App\Http\Controllers\Api\Lpk\PelatihanController;
use App\Http\Controllers\Api\PesertaPelatihanController;
use App\Http\Controllers\Api\PemaganganController;
use App\Http\Controllers\Api\TracerStudyController;
use App\Http\Controllers\Api\JobFairController;
use App\Http\Controllers\Api\JobFairPerusahaanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;

// ======================================
// PUBLIC ROUTES
// ======================================

Route::post('/login', [AuthController::class, 'login']);

// ======================================
// PROTECTED ROUTES (SANCTUM)
// ======================================

Route::get('/debug-header', function (Illuminate\Http\Request $request) {

    return response()->json([
        'authorization' => $request->header('Authorization'),
        'bearer' => $request->bearerToken(),
    ]);

});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route untuk manajemen pengguna (hanya untuk Admin)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // lpk/pelatihan harus didefinisikan sebelum lpk untuk menghindari tabrakan rute (routing conflict)
    // di mana GET /api/lpk/pelatihan ditafsirkan sebagai detail LPK (GET /api/lpk/{lpk})
    Route::apiResource('lpk/pelatihan', PelatihanController::class);

    Route::apiResource('lpk', LpkController::class);

    Route::apiResource('tenaga-kerja', TenagaKerjaController::class);

    Route::apiResource('perusahaan-mitra', PerusahaanMitraController::class);

    Route::get('sertifikasi/{id}/download', [SertifikasiController::class, 'download']);
    Route::post('sertifikasi/{id}', [SertifikasiController::class, 'update']); // multipart update
    Route::apiResource('sertifikasi', SertifikasiController::class);

    Route::post('peserta-pelatihan/import-preview', [PesertaPelatihanController::class, 'importPreview']);
    Route::post('peserta-pelatihan/import-commit', [PesertaPelatihanController::class, 'importCommit']);
    Route::get('peserta-pelatihan/import-history', [PesertaPelatihanController::class, 'importHistory']);
    Route::apiResource('peserta-pelatihan', PesertaPelatihanController::class);

    Route::apiResource('pemagangan', PemaganganController::class);

    Route::get('laporan/dashboard', [\App\Http\Controllers\Api\LaporanController::class, 'dashboard']);
    Route::post('laporan/{id}', [\App\Http\Controllers\Api\LaporanController::class, 'update']); // for multipart update
    Route::apiResource('laporan', \App\Http\Controllers\Api\LaporanController::class);
    Route::apiResource('tracer-study', TracerStudyController::class);

    Route::apiResource('job-fair', JobFairController::class);
    Route::apiResource('job-fair-perusahaan', JobFairPerusahaanController::class);

    Route::middleware('auth:sanctum')->get('/debug-token', function (Request $request) {
        return response()->json([
            'bearer' => $request->bearerToken(),
            'user' => $request->user(),
        ]);
    });

});

