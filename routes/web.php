<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenagaKerjaController;
use App\Http\Controllers\PemaganganController;
use App\Http\Controllers\JobFairController;
use App\Http\Controllers\JobFairPerusahaanController;
use App\Http\Controllers\LaporanController;

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
Route::view(
    '/perusahaan-mitra-test',
    'perusahaan-mitra-test'
);
Route::get('/', function () {
    return view('welcome');
});

Route::resource('tenaga-kerja', TenagaKerjaController::class)->except(['show']);
Route::resource('pemagangan', PemaganganController::class)->except(['show']);
Route::resource('job-fair', JobFairController::class)->except(['show']);
Route::resource('job-fair-perusahaan', JobFairPerusahaanController::class)->except(['show']);
Route::resource('laporan', LaporanController::class)->except(['show']);
