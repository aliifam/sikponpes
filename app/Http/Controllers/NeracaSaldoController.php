<?php

namespace App\Http\Controllers;

use App\Models\AccountParent;
use App\Models\Pesantren;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class NeracaSaldoController extends Controller
{
    public function export(Request $request)
    {
        $encrypted = Crypt::decrypt($request->document);
        // dd($encrypted);
        $year = $encrypted['year'];
        $month = $encrypted['month'];
        $pesantren = $encrypted['id'];

        $session = $pesantren;

        $pesantrendata = Pesantren::where('id', $session)->first();
        // dd($pesantrendata);

        if ($month == null) {
            $month = date('m');
        }

        $balance = [];

        $parents = AccountParent::with('classification.parent')->where('pesantren_id', $session)->get();
        $i = 0;
        foreach ($parents as $p) {
            $balance[$i]['parent_code'] = $p->parent_code;
            $balance[$i]['parent_name'] = $p->parent_name;
            $classification = $p->classification()->get();
            $j = 0;
            foreach ($classification as $c) {
                $balance[$i]['classification'][$j]['classification_id'] = $c->id;
                $balance[$i]['classification'][$j]['classification_name'] = $c->classification_name;
                $account = $c->account()->with('initialBalance', 'journal')->get();
                $k = 0;
                foreach ($account as $a) {
                    $balance[$i]['classification'][$j]['account'][$k]['account_id'] = $a->id;
                    $balance[$i]['classification'][$j]['account'][$k]['account_name'] = $a->account_name;
                    $balance[$i]['classification'][$j]['account'][$k]['account_code'] = $a->account_code;
                    $balance[$i]['classification'][$j]['account'][$k]['position'] = $a->position;

                    if (!$a->initialBalance()->whereYear('date', $year)->first()) {
                        $beginning_balance = 0;
                    } else {
                        $beginning_balance = $a->initialBalance()->whereYear('date', $year)->first()->amount;
                    }
                    $position = $a->position;
                    $code = $a->numberCode;

                    if ($a->journal()->exists()) {
                        $ending_balance = $beginning_balance;
                        $journals = $a->journal()->whereHas('detail', function ($q) use ($year, $month) {
                            $q->whereYear('date', $year);
                            $q->whereMonth('date', '>=', '01');
                            $q->whereMonth('date', '<=', $month);
                            // $q->whereIn(DB::RAW('month(date)'), $month);
                        })->get();
                        // dd($journals);
                        foreach ($journals as $journal) {
                            //credit same as kredit
                            if ($journal->position == "credit") {
                                $journal->position = "kredit";
                            }
                            if ($journal->position == $position) {
                                $ending_balance += $journal->amount;
                                // dd($ending_balance, $journal->detail);
                                // dd("masuk sini");
                            } else {
                                $ending_balance -= $journal->amount;
                            }
                            // dd($ending_balance);
                        }
                    } else {
                        if ($a->initialBalance()->whereYear('date', $year)->first()) {
                            $ending_balance = $beginning_balance;
                            // dd("masuk");
                        } else {
                            $ending_balance = "0";
                        }
                    }
                    $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] = $ending_balance;

                    $k++;
                }
                $j++;
            }
            $i++;
        }

        $pdf = Pdf::loadview('export.neracajalur', compact('balance', 'pesantrendata', 'year', 'month'));
        return $pdf->stream();

        // dd($balance);
    }
}
