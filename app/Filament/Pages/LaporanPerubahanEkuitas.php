<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

class LaporanPerubahanEkuitas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.laporan-perubahan-ekuitas';

    protected static ?string $navigationGroup = 'Manajemen Laporan';

    public $session;
    public $years;
    public $year;
    public $month;
    public $saldo_berjalan;

    public function mount()
    {
        $session = Filament::getTenant()->id;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
    }
}
