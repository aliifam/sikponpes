<?php

namespace App\Filament\Pages;

use App\Models\GeneralJournal;
use App\Models\Perusahaan;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BukuPembantuUtang extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.buku-pembantu-utang';

    protected static ?string $navigationGroup = 'Manajemen Buku';


    public $perusahaans;
    public $year;
    public $session;
    public $years;
    public $data;

    public function mount()
    {
        $session = Filament::getTenant()->id;
        $perusahaans = Perusahaan::all()->where('pesantren_id', $session);

        if (isset($_GET['year'], $_GET['perusahaan'])) {
            $year = $_GET['year'];
            $perusahaan = $_GET['perusahaan'];
        } else {
            $year = date('Y');
            $perusahaan = $perusahaans->first()->id;
        }

        //take the data where pesantren_id = session, peruahaan_id = perusahaan, and year = tahun, date is from detail
        $data = GeneralJournal::with('detail', 'account', 'perusahaan')
            ->whereHas('perusahaan', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->where('perusahaan_id', $perusahaan)->get();

        $years = GeneralJournal::with('detail', 'account', 'perusahaan')
            ->whereHas('perusahaan', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) {
                $q->whereNotNull('date');
            })->get()->groupBy(function ($item) {
                return $item->detail->date->format('Y');
            });

        // dd($years->toArray());
        dd($data->toArray());
        $this->years = $years;
        $this->year = $year;
        $this->perusahaans = $perusahaans;
        $this->session = $session;
        $this->data = $data;
    }
}
