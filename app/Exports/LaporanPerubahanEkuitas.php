<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\AccountParent;
use App\Models\GeneralJournal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPerubahanEkuitas implements FromView, WithStyles, WithEvents
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

        $modal_awal = Account::where('pesantren_id', $session)->where('account_name', 'Ekuitas')->first();

        if ($modal_awal) {
            $modal_awal = $modal_awal->initialBalance()->whereYear('date', $year)->first();
            $modal_awal = $modal_awal ? $modal_awal->amount : 0;
        } else {
            $modal_awal = 0;
        }

        // dd($modal_awal);

        $ekuitas_id = Account::where('pesantren_id', $session)->where('account_name', 'Ekuitas')->first()->id;
        $prive_id = Account::where('pesantren_id', $session)->where('account_name', 'Prive')->first()->id;

        // dd($ekuitas_id, $prive_id);

        $setoran_modal = 0;

        // journal detail has many general journal and general journal belongs to journal detail,  I want to get list of general journal in $year but the date is in journal detail table
        $jurnal_yg_ada_ekuitasnya = GeneralJournal::whereHas('detail', function ($q) use ($year) {
            $q->whereYear('date', $year);
        })->where('pesantren_id', $session)->where('account_id', $ekuitas_id)->get();

        // dd($jurnal_yg_ada_ekuitasnya->toArray());

        foreach ($jurnal_yg_ada_ekuitasnya as $jurnal) {
            if ($jurnal->position == 'debit') {
                $setoran_modal -= $jurnal->amount;
            } else if ($jurnal->position == 'credit') {
                $setoran_modal += $jurnal->amount;
            }
        }

        $prive_awal = Account::where('pesantren_id', $session)->where('account_name', 'Prive')->first();

        if ($prive_awal) {
            $prive_awal = $prive_awal->initialBalance()->whereYear('date', $year)->first();
            $prive_awal = $prive_awal ? $prive_awal->amount : 0;
        } else {
            $prive_awal = 0;
        }

        $jurnal_yg_ada_privenya = GeneralJournal::whereHas('detail', function ($q) use ($year) {
            $q->whereYear('date', $year);
        })->where('pesantren_id', $session)->where('account_id', $prive_id)->get();

        foreach ($jurnal_yg_ada_privenya as $jurnal) {
            if ($jurnal->position == 'debit') {
                $prive_awal -= $jurnal->amount;
            } else if ($jurnal->position == 'credit') {
                $prive_awal += $jurnal->amount;
            }
        }


        $surpdef = 0;
        $parent = AccountParent::with('classification.account')->where('pesantren_id', $session)->get();

        $incomeData = [];
        $expenseData = [];
        $income = 0;
        $expense = 0;

        foreach ($parent as $p) {
            $i = 0;
            $classification = $p->classification()->get();
            foreach ($classification as $c) {
                $account = $c->account()->get();
                foreach ($account as $a) {
                    $position = $a->position;
                    if (!$a->initialBalance()->whereYear('date', $year)->first()) {
                        $beginningBalance = 0;
                    } else {
                        $beginningBalance = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }
                    if ($a->journal()->exists()) {
                        $endingBalance = $beginningBalance;
                        $jurnals = $a->journal()->whereHas('detail', function ($q) use ($year, $month) {
                            $q->whereYear('date', $year);
                            $q->whereMonth('date', '>=', '01');
                            $q->whereMonth('date', '<=', $month);
                        })->get();
                        foreach ($jurnals as $jurnal) {
                            if ($jurnal->position == "credit") {
                                $jurnal->position = "kredit";
                            }
                            if ($jurnal->position == $position) {
                                $endingBalance += $jurnal->amount;
                            } else {
                                $endingBalance -= $jurnal->amount;
                            }
                        }
                    } else {
                        if ($a->initialBalance()->whereYear('date', $year)->first()) {
                            $endingBalance = $beginningBalance;
                        } else {
                            $endingBalance = 0;
                        }
                    }

                    if ($p->parent_name == "Pendapatan") {
                        $incomeData[$i]['classification'] = $c->classification_name;
                        $incomeData[$i]['classification_code'] = $c->classification_code;
                        $incomeData[$i]['name'][] = $a->account_name;
                        $incomeData[$i]['code'][] = $a->account_code;
                        $incomeData[$i]['ending balance'][] = $endingBalance;
                        if ($position == "kredit") {
                            $income += $endingBalance;
                        } else {
                            $income -= $endingBalance;
                        }
                    } else if ($p->parent_name == "Biaya") {
                        $expenseData[$i]['classification'] = $c->classification_name;
                        $expenseData[$i]['classification_code'] = $c->classification_code;
                        $expenseData[$i]['name'][] = $a->account_name;
                        $expenseData[$i]['code'][] = $a->account_code;
                        $expenseData[$i]['ending balance'][] = $endingBalance;
                        if ($position == "debit") {
                            $expense += $endingBalance;
                        } else {
                            $expense -= $endingBalance;
                        }
                    }
                }
                $i++;
            }
        }

        $surpdef = $income - $expense;

        $prive = $prive_awal;

        return view('export.excel.laporan_perubahan_ekuitas', [
            'surpdef' => $surpdef,
            'prive' => $prive,
            'setoran_modal' => $setoran_modal,
            'modal_awal' => $modal_awal,
            'year' => $year,
            'month' => $month,
            'pesantrendata' => $this->pesantren
        ]);
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

                $event->sheet->getStyle('A4:b11')->ApplyFromArray($styleArray);
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(70);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
            },
        ];
    }
}
