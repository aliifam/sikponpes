<?php

namespace App\Filament\Pages;

use App\Models\GeneralJournal;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use App\Models\Santri;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class BukuPembantuPiutang extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.buku-pembantu-piutang';

    protected static ?string $navigationGroup = 'Manajemen Buku';

    public $santris;
    public $year;
    public $session;
    public $years;
    public $data;
    public $santriName;
    public $santri;

    public function mount()
    {
        $session = Filament::getTenant()->id;
        $santris = Santri::all()->where('pesantren_id', $session);

        if (isset($_GET['year'], $_GET['santri'])) {
            $year = $_GET['year'];
            $santri = $_GET['santri'];
        } else {
            $year = date('Y');
            //if santris is empty, set default santri to null
            if (!$santris->isEmpty()) {
                $santri = $santris->first()->id;
            } else {
                $santri = null;
            }
        }

        //take the data where pesantren_id = session, peruahaan_id = perusahaan, and year = tahun, date is from detail
        $data = GeneralJournal::with('detail', 'account', 'santri')
            ->whereHas('santri', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->where('santri_id', $santri)->get();

        $years = JournalDetail::selectRaw('YEAR(date) as year')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->union(InitialBalance::selectRaw('YEAR(date) as year')
                ->whereHas('account.classification.parent', function ($q) use ($session) {
                    $q->where('pesantren_id', $session);
                }))->orderBy('year', 'DESC')->get();

        // if years is empty, set default year to current year
        if ($years->isEmpty()) {
            //same data structure as $years
            $years = collect([
                (object) [
                    'year' => date('Y')
                ]
            ]);
        }

        if ($santri != null) {
            $santriName = Santri::where('id', $santri)->first()->nama;
        } else {
            $santriName = null;
        }

        $this->session = $session;
        $this->santris = $santris;
        $this->year = $year;
        $this->years = $years;
        $this->data = $data;
        $this->santriName = $santriName;
        $this->santri = $santri;
    }
}
