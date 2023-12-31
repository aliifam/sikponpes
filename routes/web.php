<?php

use App\Http\Controllers\LAController;
use App\Http\Controllers\LAKController;
use App\Http\Controllers\LPEController;
use App\Http\Controllers\LPKController;
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

// neraca lajur pdf
Route::get('export/neraca-lajur', [NeracaSaldoController::class, 'export'])->name('export.neraca-lajur')->middleware('auth');

// laporan aktivitas pdf & excel
Route::get('export/laporan-aktivitas', [LAController::class, 'exportpdf'])->name('export.laporan-aktivitas')->middleware('auth');
Route::get('export/laporan-aktivitas_excel', [LAController::class, 'exportexcel'])->name('export.laporan-aktivitas_excel')->middleware('auth');

// laporan arus kas pdf & excel
Route::get('export/laporan-arus-kas', [LAKController::class, 'exportpdf'])->name('export.laporan-arus-kas')->middleware('auth');
Route::get('export/laporan-arus-kas-excel', [LAKController::class, 'exportexcel'])->name('export.laporan-arus-kas-excel')->middleware('auth');

// laporan perubahan ekuitas pdf & excel
Route::get('export/laporan-perubahan-ekuitas', [LPEController::class, 'exportpdf'])->name('export.laporan-perubahan-ekuitas')->middleware('auth');
Route::get('export/laporan-perubahan-ekuitas-excel', [LPEController::class, 'exportexcel'])->name('export.laporan-perubahan-ekuitas-excel')->middleware('auth');

// laporan posisi keuangan pdf & excel
Route::get('export/laporan-posisi-keuangan', [LPKController::class, 'exportpdf'])->name('export.laporan-posisi-keuangan')->middleware('auth');
Route::get('export/laporan-posisi-keuangan-excel', [LPKController::class, 'exportexcel'])->name('export.laporan-posisi-keuangan-excel')->middleware('auth');
