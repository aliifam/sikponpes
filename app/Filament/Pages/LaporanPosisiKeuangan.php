<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanPosisiKeuangan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.pages.laporan-posisi-keuangan';
    protected static ?string $navigationGroup = 'Manajemen Laporan';
}
