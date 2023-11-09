<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanArusKas implements FromView, WithStyles, WithEvents
{
    private $pesantren;
    private $year;
    private $month;

    use Exportable;

    public function __construct($pesantren, $year, $month)
    {
        $this->pesantren = $pesantren;
        $this->year = $year;
        $this->month = $month;
    }


    public function view(): View
    {
        $session = $this->pesantren->id;
        $year = $this->year;
        $month = $this->month;

        $id_kas = Account::with('classification.parent')
            ->whereHas('classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->where('account_name', 'Kas')->first()->id;

        $id_bank = Account::with('classification.parent')
            ->whereHas('classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->where('account_name', 'Bank')->first()->id;

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
                    $journal['account']['classification']['classification_name'] === 'Aset Tetap' ||
                    $journal['account']['classification']['classification_name'] === 'Investasi'
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
                    $journal['account']['classification']['classification_name'] === 'Aset Tetap' ||
                    $journal['account']['classification']['classification_name'] === 'Investasi'
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

        //saldo awal is initial balance of kas account and bank account
        if (InitialBalance::where('account_id', $id_kas)->whereYear('date', '=', $year)->first() == null) {
            $initial_kas = 0;
        } else {
            $initial_kas = InitialBalance::where('account_id', $id_kas)->whereYear('date', '=', $year)->first()->amount;
        }

        if (InitialBalance::where('account_id', $id_bank)->whereYear('date', '=', $year)->first() == null) {
            $initial_bank = 0;
        } else {
            $initial_bank = InitialBalance::where('account_id', $id_bank)->whereYear('date', '=', $year)->first()->amount;
        }

        $saldoAwal = $initial_kas + $initial_bank;

        $pesantren = $this->pesantren;

        $kasId = $id_kas;

        return view('export.excel.laporan_arus_kas', compact('pesantren', 'year', 'month', 'arusKasOperasi', 'arusKasInvestasi', 'arusKasPendanaan', 'saldoAwal', 'kasId'));
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 18
                ]
            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 18
                ]
            ],

            // Styling a specific cell by coordinate.
            3 => [
                'font' => [
                    'italic' => true,
                    'size' => 16
                ]
            ],


            // Styling an entire column.
            'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function registerEvents(): array
    {
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        return [
            AfterSheet::class => function (AfterSheet $event) use (
                $styleArray
            ) {

                // $event->sheet->getStyle('A4:b11')->ApplyFromArray($styleArray);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(70);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
            },
        ];
    }
}
