<?php

use App\Http\Controllers\LaporanAktifitasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NeracaSaldoController;
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
    return view('landing');
});

Route::get('export/neraca_lajur', [NeracaSaldoController::class, 'export'])->name('export.neraca_lajur');

Route::get('export/laporan_aktivitas', [LaporanAktifitasController::class, 'exportpdf'])->name('export.laporan_aktivitas');
