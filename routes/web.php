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
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tenaga-kerja', TenagaKerjaController::class)->except(['show']);
Route::resource('pemagangan', PemaganganController::class)->except(['show']);
Route::resource('job-fair', JobFairController::class)->except(['show']);
Route::resource('job-fair-perusahaan', JobFairPerusahaanController::class)->except(['show']);
Route::resource('laporan', LaporanController::class)->except(['show']);
