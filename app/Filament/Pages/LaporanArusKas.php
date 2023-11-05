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

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        $id_kas = Account::with('classification.parent')
            ->whereHas('classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->where('account_name', 'Kas')->first()->id;

        //list all journal
        $journal = JournalDetail::with('general_journal.account.classification.parent')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->whereHas('general_journal', function ($q) use ($year, $month) {
                $q->whereYear('date', $year);
                $q->whereMonth('date', '>=', '01');
                $q->whereMonth('date', '<=', $month);
            })->get();
        // dd($journal->toArray());

        // filter journal that includes account kas in general journals
        $journal = $journal->filter(function ($item) use ($id_kas) {
            return collect($item['general_journal'])->contains('account_id', $id_kas);
        });

        // arus kas operasi masuk where account classification is aset lancar or utang jangka pendek and kas account position is debit
        function filterOperasiMasuk($item, $id_kas)
        {
            $kasAccountFound = false;
            $otherAccountStatus = false;
            foreach ($item['general_journal'] as $journal) {
                // Check if the "account_code" is "1.1.1" (Kas) and the "position" is "debit"

                if ($journal['account']['id'] == $id_kas && $journal['position'] === 'debit') {
                    $kasAccountFound = true;
                    continue;
                }
                // Check if the "position" is "debit" and the "classification_name" is "Aset Lancar" or "Utang Jangka Pendek"
                if (
                    $journal['account']['classification']['classification_name'] === 'Aset Lancar' ||
                    $journal['account']['classification']['classification_name'] === 'Utang Jangka Pendek' ||
                    $journal['account']['account_name'] === 'Prive'
                ) {
                    // dd("masuk");
                    $otherAccountStatus = true;
                }
            }

            if ($kasAccountFound && $otherAccountStatus) {
                return true; // Include the item in the result
            } else {
                return false; // Exclude the item from the result
            }
        }

        // arus kas operasi keluar where account classification is aset lancar or utang jangka pendek and kas position is credit
        function filterOperasiKeluar($item, $id_kas)
        {
            $kasAccountFound = false;
            $otherAccountStatus = false;
            foreach ($item['general_journal'] as $journal) {
                // Check if the "account_code" is "1.1.1" (Kas) and the "position" is "debit"

                if ($journal['account']['id'] == $id_kas && $journal['position'] === 'credit') {
                    $kasAccountFound = true;
                    //goto next loop
                    continue;
                }
                // Check if the "position" is "debit" and the "classification_name" is "Aset Lancar" or "Utang Jangka Pendek"
                if (
                    $journal['account']['classification']['classification_name'] === 'Aset Lancar' ||
                    $journal['account']['classification']['classification_name'] === 'Utang Jangka Pendek' ||
                    $journal['account']['account_name'] === 'Prive'
                ) {
                    // dd("masuk");
                    $otherAccountStatus = true;
                }
            }

            if ($kasAccountFound && $otherAccountStatus) {
                return true; // Include the item in the result
            } else {
                return false; // Exclude the item from the result
            }
        }

        $arusKasOperasiMasuk = $journal->filter(function ($item) use ($id_kas) {
            return filterOperasiMasuk($item, $id_kas);
        });

        $arusKasOperasiKeluar = $journal->filter(function ($item) use ($id_kas) {
            return filterOperasiKeluar($item, $id_kas);
        });

        $arusKasOperasi = [
            'masuk' => $arusKasOperasiMasuk->toArray(),
            'keluar' => $arusKasOperasiKeluar->toArray(),
            //amount is operasi masuk amount of kas account - operasi keluar amount of kas account
            'amount' => $arusKasOperasiMasuk->sum(function ($item) use ($id_kas) {
                return $item['general_journal']->where('account_id', $id_kas)->where('position', 'debit')->sum('amount');
            }) - $arusKasOperasiKeluar->sum(function ($item) use ($id_kas) {
                return $item['general_journal']->where('account_id', $id_kas)->where('position', 'credit')->sum('amount');
            })
        ];

        // dd($arusKasOperasi);

        function filterInvestasiMasuk($item, $id_kas)
        {
            $kasAccountFound = false;
            $otherAccountStatus = false;
            foreach ($item['general_journal'] as $journal) {
                // Check if the "account_code" is "1.1.1" (Kas) and the "position" is "debit"

                if ($journal['account']['id'] == $id_kas && $journal['position'] === 'debit') {
                    $kasAccountFound = true;
                    continue;
                }
                // Check if the "position" is "debit" and the "classification_name" is "Aset Lancar" or "Utang Jangka Pendek"
                if (
                    $journal['account']['classification']['classification_name'] === 'Aset Tetap'
                ) {
                    // dd("masuk");
                    $otherAccountStatus = true;
                }
            }

            if ($kasAccountFound && $otherAccountStatus) {
                return true; // Include the item in the result
            } else {
                return false; // Exclude the item from the result
            }
        }

        function filterInvestasiKeluar($item, $id_kas)
        {
            $kasAccountFound = false;
            $otherAccountStatus = false;
            foreach ($item['general_journal'] as $journal) {
                // Check if the "account_code" is "1.1.1" (Kas) and the "position" is "debit"

                if ($journal['account']['id'] == $id_kas && $journal['position'] === 'credit') {
                    $kasAccountFound = true;
                    continue;
                }
                // Check if the "position" is "debit" and the "classification_name" is "Aset Lancar" or "Utang Jangka Pendek"
                if (
                    $journal['account']['classification']['classification_name'] === 'Aset Tetap'
                ) {
                    // dd("masuk");
                    $otherAccountStatus = true;
                }
            }

            if ($kasAccountFound && $otherAccountStatus) {
                return true; // Include the item in the result
            } else {
                return false; // Exclude the item from the result
            }
        }

        $arusKasInvestasiMasuk = $journal->filter(function ($item) use ($id_kas) {
            return filterInvestasiMasuk($item, $id_kas);
        });

        $arusKasInvestasiKeluar = $journal->filter(function ($item) use ($id_kas) {
            return filterInvestasiKeluar($item, $id_kas);
        });

        $arusKasInvestasi = [
            'masuk' => $arusKasInvestasiMasuk->toArray(),
            'keluar' => $arusKasInvestasiKeluar->toArray(),
            //amount is investasi masuk amount of kas account - investasi keluar amount of kas account
            'amount' => $arusKasInvestasiMasuk->sum(function ($item) use ($id_kas) {
                return $item['general_journal']->where('account_id', $id_kas)->where('position', 'debit')->sum('amount');
            }) - $arusKasInvestasiKeluar->sum(function ($item) use ($id_kas) {
                return $item['general_journal']->where('account_id', $id_kas)->where('position', 'credit')->sum('amount');
            })
        ];

        // dd($arusKasInvestasi);

        //arus kas pendanaan
        function filterPendanaanMasuk($item, $id_kas)
        {
            $kasAccountFound = false;
            $otherAccountStatus = false;
            foreach ($item['general_journal'] as $journal) {
                // Check if the "account_code" is "1.1.1" (Kas) and the "position" is "debit"

                if ($journal['account']['id'] == $id_kas && $journal['position'] === 'debit') {
                    $kasAccountFound = true;
                    continue;
                }
                // Check if the "position" is "debit" and the "classification_name" is "Aset Lancar" or "Utang Jangka Pendek"
                if (
                    $journal['account']['classification']['classification_name'] === 'Ekuitas' ||
                    $journal['account']['classification']['classification_name'] === 'Utang Jangka Panjang'
                ) {
                    // dd("masuk");
                    $otherAccountStatus = true;
                }
            }

            if ($kasAccountFound && $otherAccountStatus) {
                return true; // Include the item in the result
            } else {
                return false; // Exclude the item from the result
            }
        }

        function filterPendanaanKeluar($item, $id_kas)
        {
            $kasAccountFound = false;
            $otherAccountStatus = false;
            foreach ($item['general_journal'] as $journal) {
                // Check if the "account_code" is "1.1.1" (Kas) and the "position" is "debit"

                if ($journal['account']['id'] == $id_kas && $journal['position'] === 'credit') {
                    $kasAccountFound = true;
                    continue;
                }
                // Check if the "position" is "debit" and the "classification_name" is "Aset Lancar" or "Utang Jangka Pendek"
                if (
                    $journal['account']['classification']['classification_name'] === 'Ekuitas' ||
                    $journal['account']['classification']['classification_name'] === 'Utang Jangka Panjang'
                ) {
                    // dd("masuk");
                    $otherAccountStatus = true;
                }
            }

            if ($kasAccountFound && $otherAccountStatus) {
                return true; // Include the item in the result
            } else {
                return false; // Exclude the item from the result
            }
        }

        $arusKasPendanaanMasuk = $journal->filter(function ($item) use ($id_kas) {
            return filterPendanaanMasuk($item, $id_kas);
        });

        $arusKasPendanaanKeluar = $journal->filter(function ($item) use ($id_kas) {
            return filterPendanaanKeluar($item, $id_kas);
        });

        $arusKasPendanaan = [
            'masuk' => $arusKasPendanaanMasuk->toArray(),
            'keluar' => $arusKasPendanaanKeluar->toArray(),
            //amount is pendanaan masuk amount of kas account - pendanaan keluar amount of kas account
            'amount' => $arusKasPendanaanMasuk->sum(function ($item) use ($id_kas) {
                return $item['general_journal']->where('account_id', $id_kas)->where('position', 'debit')->sum('amount');
            }) - $arusKasPendanaanKeluar->sum(function ($item) use ($id_kas) {
                return $item['general_journal']->where('account_id', $id_kas)->where('position', 'credit')->sum('amount');
            })
        ];

        // dd($arusKasPendanaan);

        $this->arusKasOperasi = $arusKasOperasi;
        $this->arusKasInvestasi = $arusKasInvestasi;
        $this->arusKasPendanaan = $arusKasPendanaan;
        $this->session = $session;
        $this->years = JournalDetail::selectRaw('YEAR(date) as year')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->groupBy('year')->orderBy('year', 'desc')->get();
        $this->year = $year;
        $this->month = $month;




        // arus kas operasi keluar


        //arus kas operasi
        // $arusKasOperasi = GeneralJournal::with('account.classification.parent', 'detail')
        //     ->whereHas('account.classification.parent', function ($q) use ($session) {
        //         $q->where('pesantren_id', $session);
        //     })->whereHas('detail', function ($q) use ($year) {
        //         $q->whereYear('date', $year);
        //     })->where('account_id', $id_kas)
        //     ->get();
        // // dd($arusKasOperasi->toArray());

        // //if kas position is debit and account classification is aset lancar or utang jangka pendek
        // $arusKasOperasiMasuk = $arusKasOperasi->where(function ($item) {
        //     return ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Aset Lancar')
        //         || ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Pendek');
        // });
        // //if kas position is credit and account classification is aset lancar
        // $arusKasOperasiKeluar = $arusKasOperasi->where(function ($item) {
        //     return ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Aset Lancar')
        //         || ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Pendek');
        // });

        // $arusKasOperasi = [
        //     'masuk' => $arusKasOperasiMasuk->toArray(),
        //     'keluar' => $arusKasOperasiKeluar->toArray(),
        //     'amount' => $arusKasOperasiMasuk->sum('amount') - $arusKasOperasiKeluar->sum('amount')
        // ];
        // // dd($arusKasOperasi);

        // //arus kas investasi
        // $arusKasInvestasi = GeneralJournal::with('account.classification.parent', 'detail')
        //     ->whereHas('account.classification.parent', function ($q) use ($session) {
        //         $q->where('pesantren_id', $session);
        //     })->whereHas('detail', function ($q) use ($year) {
        //         $q->whereYear('date', $year);
        //     })->where('account_id', $id_kas)
        //     ->get();

        // //if kas position is debit and account classification is aset tetap
        // $arusKasInvestasiMasuk = $arusKasInvestasi->where('position', 'debit')->where('account.classification.classification_name', 'Aset Tetap');
        // //if kas position is credit and account classification is aset tetap
        // $arusKasInvestasiKeluar = $arusKasInvestasi->where('position', 'credit')->where('account.classification.classification_name', 'Aset Tetap');

        // $arusKasInvestasi = [
        //     'masuk' => $arusKasInvestasiMasuk->toArray(),
        //     'keluar' => $arusKasInvestasiKeluar->toArray(),
        //     'amount' => $arusKasInvestasiMasuk->sum('amount') - $arusKasInvestasiKeluar->sum('amount')
        // ];

        // // dd($arusKasInvestasi);

        // //arus kas pendanaan
        // $arusKasPendanaan = GeneralJournal::with('account.classification.parent', 'detail')
        //     ->whereHas('account.classification.parent', function ($q) use ($session) {
        //         $q->where('pesantren_id', $session);
        //     })->whereHas('detail', function ($q) use ($year) {
        //         $q->whereYear('date', $year);
        //     })->where('account_id', $id_kas)
        //     ->get();

        // //if kas position is debit and account classification is ekuitas or utang jangka panjang
        // $arusKasPendanaanMasuk = $arusKasPendanaan->where(function ($item) {
        //     return ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Ekuitas')
        //         || ($item['position'] === 'debit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Panjang');
        // });

        // //if kas position is credit and account classification is ekuitas or utang jangka panjang
        // $arusKasPendanaanKeluar = $arusKasPendanaan->where(function ($item) {
        //     return ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Ekuitas')
        //         || ($item['position'] === 'credit' && $item['account']['classification']['classification_name'] === 'Utang Jangka Panjang');
        // });

        // $arusKasPendanaan = [
        //     'masuk' => $arusKasPendanaanMasuk->toArray(),
        //     'keluar' => $arusKasPendanaanKeluar->toArray(),
        //     'amount' => $arusKasPendanaanMasuk->sum('amount') - $arusKasPendanaanKeluar->sum('amount')
        // ];

        // dd($arusKasPendanaan);

        //saldo awal adalah
        // $saldoAwal = InitialBalance::where


    }
}
