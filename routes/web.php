<?php

use Illuminate\Support\Facades\Route;

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