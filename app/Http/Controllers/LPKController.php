<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPosisiKeuangan;
use App\Models\AccountParent;
use App\Models\Pesantren;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class LPKController extends Controller
{
    public function exportpdf(Request $request)
    {
        try {
            $encrypted = Crypt::decrypt($request->document);
            // Your code to work with the decrypted data
        } catch (DecryptException $e) {
            // Handle the exception by returning JavaScript to close the tab or window
            return response('Unauthorized', 403);
        }

        $year = $encrypted['year'];
        $month = $encrypted['month'];
        $session = $encrypted['id'];

        $pesantrendata = Pesantren::where('id', $session)->first();

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

        //export pdf
        $pdf = Pdf::loadView('export.pdf.laporan_posisi_keuangan', [
            'assetData' => $assetData,
            'liabilityData' => $liabilityData,
            'equityData' => $equityData,
            'aktiva' => $aktiva,
            'pasiva' => $pasiva,
            'asset' => $asset,
            'liability' => $liability,
            'equity' => $equity,
            'pesantrendata' => $pesantrendata,
            'year' => $year,
            'month' => $month,
        ]);

        // filename = "Laporan posisi keuangan" + "-" + pesantren name + "-" + year + "-" + month
        return $pdf->stream('Laporan posisi keuangan' . '-' . $pesantrendata->name . '-' . $year . '-' . $month . '.pdf');
    }

    public function exportexcel(Request $request)
    {
        try {
            $encrypted = Crypt::decrypt($request->document);
            // Your code to work with the decrypted data
        } catch (DecryptException $e) {
            // Handle the exception by returning JavaScript to close the tab or window
            return response('Unauthorized', 403);
        }

        $year = $encrypted['year'];
        $month = $encrypted['month'];
        $session = $encrypted['id'];

        $pesantrendata = Pesantren::where('id', $session)->first();

        return Excel::download(new LaporanPosisiKeuangan($pesantrendata, $year, $month), 'Laporan posisi keuangan' . '-' . $pesantrendata->name . '-' . $year . '-' . $month . '.xlsx');
    }
}
