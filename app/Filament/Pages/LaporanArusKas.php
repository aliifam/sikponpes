<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\GeneralJournal;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class LaporanArusKas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string $view = 'filament.pages.laporan-arus-kas';

    protected static ?string $navigationGroup = 'Manajemen Laporan';

    //arus kas operasi
    //arus kas investasi
    //arus kas pendanaan

    public $arusKasOperasi;
    public $arusKasInvestasi;
    public $arusKasPendanaan;
    public $session;
    public $years;
    public $year;
    public $month;

    public function mount()
    {
        $session = Filament::getTenant()->id;

        if (isset($_GET['year'], $_GET['akun'])) {
            $year = $_GET['year'];
            $akun = $_GET['month'];
        } else {
            $year = date('Y');
            $akun = date('m');
        }
        $id_kas = Account::with('classification.parent')
            ->whereHas('classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->where('account_name', 'Kas')->first()->id;


        //arus kas operasi
        $arusKasOperasi = GeneralJournal::with('account.classification.parent', 'detail')
            ->whereHas('account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->where('account_id', $id_kas)
            ->get();
        // dd($arusKasOperasi->toArray());

        //if kas position is debit and account classification is aset lancar
        $arusKasOperasiMasuk = $arusKasOperasi->where('position', 'debit')->where('account.classification.classification_name', 'Aset Lancar');
        //if kas position is credit and account classification is aset lancar
        $arusKasOperasiKeluar = $arusKasOperasi->where('position', 'credit')->where('account.classification.classification_name', 'Aset Lancar');

        $arusKasOperasi = [
            'masuk' => $arusKasOperasiMasuk->toArray(),
            'keluar' => $arusKasOperasiKeluar->toArray(),
            'amount' => $arusKasOperasiMasuk->sum('amount') - $arusKasOperasiKeluar->sum('amount')
        ];
        // dd($arusKasOperasi);

        $cobadata = JournalDetail::with('general_journal.account.classification.parent')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('general_journal', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->get();
        dd($cobadata->toArray());
    }
}
