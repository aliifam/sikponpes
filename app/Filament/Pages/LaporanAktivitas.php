<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanAktivitas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string $view = 'filament.pages.laporan-aktivitas';

    protected static ?string $navigationGroup = 'Manajemen Laporan';

    public $balance;
    public $years;
    public $year;
    public $month;
    public $session;


    public function mount()
    {
    }
}
