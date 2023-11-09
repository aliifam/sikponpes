<?php

namespace App\Exports;

use App\Models\AccountParent;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPosisiKeuangan implements FromView, WithStyles, WithEvents
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

        $parent = AccountParent::with('classification.account')->where('pesantren_id', $session)->get();

        $assetData = [];
        $liabilityData = [];
        $equityData = [];

        $aktiva = 0;
        $pasiva = 0;
        $asset = 0;
        $liability = 0;
        $equity = 0;

        foreach ($parent as $p) {
            $i = 0;
            $classification = $p->classification()->get();
            foreach ($classification as $c) {
                $sum = 0;
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

                    if ($p->parent_name == "Aset") {
                        $assetData[$i]['classification'] = $c->classification_name;
                        $assetData[$i]['classification_code'] = $c->classification_code;
                        $assetData[$i]['name'][] = $a->account_name;
                        $assetData[$i]['code'][] = $a->account_code;
                        $assetData[$i]['ending balance'][] = $endingBalance;
                        if ($position == "debit") {
                            $aktiva += $endingBalance;
                            $sum += $endingBalance;
                        } else {
                            $aktiva -= $endingBalance;
                            $sum -= $endingBalance;
                        }
                        $assetData[$i]['sum'] = $sum;
                    } else if ($p->parent_name == "Liabilitas") {
                        $liabilityData[$i]['classification'] = $c->classification_name;
                        $liabilityData[$i]['classification_code'] = $c->classification_code;
                        $liabilityData[$i]['name'][] = $a->account_name;
                        $liabilityData[$i]['code'][] = $a->account_code;
                        $liabilityData[$i]['ending balance'][] = $endingBalance;
                        if ($position == "kredit") {
                            $sum += $endingBalance;
                            $pasiva += $endingBalance;
                        } else {
                            $sum -= $endingBalance;
                            $pasiva -= $endingBalance;
                        }
                        $liabilityData[$i]['sum'] = $sum;
                    } else if ($p->parent_name == "Ekuitas") {
                        $equityData[$i]['classification'] = $c->classification_name;
                        $equityData[$i]['classification_code'] = $c->classification_code;
                        $equityData[$i]['name'][] = $a->account_name;
                        $equityData[$i]['code'][] = $a->account_code;
                        $equityData[$i]['ending balance'][] = $endingBalance;
                        if ($position == "kredit") {
                            $sum += $endingBalance;
                            $pasiva += $endingBalance;
                        } else {
                            $sum += $endingBalance;
                            $pasiva -= $endingBalance;
                        }
                    }
                }
                $i++;
            }
        }

        $pesantren = $this->pesantren;

        return view('export.excel.laporan_posisi_keuangan', compact('assetData', 'liabilityData', 'equityData', 'aktiva', 'pasiva', 'pesantren', 'year', 'month', 'asset', 'liability', 'equity'));
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
