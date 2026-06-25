<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TenagaKerjaController;
use App\Http\Controllers\Api\PemaganganController;
use App\Http\Controllers\Api\JobFairController;
use App\Http\Controllers\Api\JobFairPerusahaanController;
use App\Http\Controllers\Api\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Route untuk halaman testing frontend sederhana.
|
*/

Route::view('/', 'login');

Route::view('/lpk-test', 'lpk-test');
Route::view('/tenaga-kerja-test', 'tenaga-kerja-test');
Route::view('/perusahaan-mitra-test', 'perusahaan-mitra-test');
Route::view('/sertifikasi-test', 'sertifikasi-test');
Route::view('/job-fair-test', 'job-fair-test');
Route::view('/job-fair-perusahaan-test', 'job-fair-perusahaan-test');
Route::view('/laporan-test', 'laporan-test');
Route::view('/pelatihan-test', 'pelatihan-test');
Route::view('/pemagangan-test', 'pemagangan-test');
Route::view('/peserta-pelatihan-test', 'peserta-pelatihan-test');
Route::view('/tracer-study-test', 'tracer-study-test');

Route::get('/welcome', function () {
    return view('welcome');
});

Route::resource('tenaga-kerja', TenagaKerjaController::class)->except(['show']);
Route::resource('pemagangan', PemaganganController::class)->except(['show']);
Route::resource('job-fair', JobFairController::class)->except(['show']);
Route::resource('job-fair-perusahaan', JobFairPerusahaanController::class)->except(['show']);
Route::resource('laporan', LaporanController::class)->except(['show']);
