<?php

namespace App\Http\Controllers;

use App\Exports\LaporanAktivitas;
use App\Models\AccountParent;
use App\Models\Pesantren;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class LAController extends Controller
{
    public function exportpdf(Request $request)
    {
        $encrypted = Crypt::decrypt($request->document);

        $year = $encrypted['year'];
        $month = $encrypted['month'];
        $session = $encrypted['id'];

        $pesantrendata = Pesantren::where('id', $session)->first();

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

        $pdf = Pdf::loadView('export.pdf.laporan_aktifitas', compact('pesantrendata', 'incomeData', 'expenseData', 'income', 'expense', 'year', 'month'));
        return $pdf->stream();
    }

    public function exportexcel(Request $request)
    {
        $pesantren = $request->id;
        $year = $request->year;
        $month = $request->month;

        return Excel::download(new LaporanAktivitas($pesantren, $year, $month), 'laporan-aktivitas-' . $year . '-' . $month . '.xlsx');
    }
}
