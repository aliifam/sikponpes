<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\GeneralJournal;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class LaporanArusKas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string $view = 'filament.pages.laporan-arus-kas';

    protected static ?string $navigationGroup = 'Manajemen Laporan';

    // Aktivitas operasi : aset lancar, utang jangka pendek
    // Aktivitas investasi : aset tetap (penjualan aset tetap, pelunasan piutang secara berangsur-angsur)
    // Aktivitas pendanaan : utang jangka panjang, ekuitas (pelunasan pinjaman bank)

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
    public $saldoAwal;

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

        //if kas position is debit and account classification is aset lancar or utang jangka pendek
        $arusKasOperasiMasuk = $arusKasOperasi->where(function ($item) {
            return ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Aset Lancar')
                || ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Pendek');
        });
        //if kas position is credit and account classification is aset lancar
        $arusKasOperasiKeluar = $arusKasOperasi->where(function ($item) {
            return ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Aset Lancar')
                || ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Pendek');
        });

        $arusKasOperasi = [
            'masuk' => $arusKasOperasiMasuk->toArray(),
            'keluar' => $arusKasOperasiKeluar->toArray(),
            'amount' => $arusKasOperasiMasuk->sum('amount') - $arusKasOperasiKeluar->sum('amount')
        ];
        dd($arusKasOperasi);

        //arus kas investasi
        $arusKasInvestasi = GeneralJournal::with('account.classification.parent', 'detail')
            ->whereHas('account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->where('account_id', $id_kas)
            ->get();

        //if kas position is debit and account classification is aset tetap
        $arusKasInvestasiMasuk = $arusKasInvestasi->where('position', 'debit')->where('account.classification.classification_name', 'Aset Tetap');
        //if kas position is credit and account classification is aset tetap
        $arusKasInvestasiKeluar = $arusKasInvestasi->where('position', 'credit')->where('account.classification.classification_name', 'Aset Tetap');

        $arusKasInvestasi = [
            'masuk' => $arusKasInvestasiMasuk->toArray(),
            'keluar' => $arusKasInvestasiKeluar->toArray(),
            'amount' => $arusKasInvestasiMasuk->sum('amount') - $arusKasInvestasiKeluar->sum('amount')
        ];

        // dd($arusKasInvestasi);

        //arus kas pendanaan
        $arusKasPendanaan = GeneralJournal::with('account.classification.parent', 'detail')
            ->whereHas('account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('detail', function ($q) use ($year) {
                $q->whereYear('date', $year);
            })->where('account_id', $id_kas)
            ->get();

        //if kas position is debit and account classification is ekuitas or utang jangka panjang
        $arusKasPendanaanMasuk = $arusKasPendanaan->where(function ($item) {
            return ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Ekuitas')
                || ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Panjang');
        });

        //if kas position is credit and account classification is ekuitas or utang jangka panjang
        $arusKasPendanaanKeluar = $arusKasPendanaan->where(function ($item) {
            return ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Ekuitas')
                || ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Panjang');
        });

        $arusKasPendanaan = [
            'masuk' => $arusKasPendanaanMasuk->toArray(),
            'keluar' => $arusKasPendanaanKeluar->toArray(),
            'amount' => $arusKasPendanaanMasuk->sum('amount') - $arusKasPendanaanKeluar->sum('amount')
        ];

        // dd($arusKasPendanaan);

        //saldo awal adalah
        // $saldoAwal = InitialBalance::where

        // $cobadata = JournalDetail::with('general_journal.account.classification.parent')
        //     ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
        //         $q->where('pesantren_id', $session);
        //     })->whereHas('general_journal', function ($q) use ($year) {
        //         $q->whereYear('date', $year);
        //     })->get();
        // dd($cobadata->toArray());
    }
}
