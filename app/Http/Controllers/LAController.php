<?php

namespace App\Http\Controllers;

use App\Exports\LaporanAktivitas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LAController extends Controller
{
    public function exportpdf(Request $request)
    {
        $pesantren = $request->id;
        $year = $request->year;
        $month = $request->month;
    }

    public function exportexcel(Request $request)
    {
        $pesantren = $request->id;
        $year = $request->year;
        $month = $request->month;

        return Excel::download(new LaporanAktivitas($pesantren, $year, $month), 'laporan-aktivitas-' . $year . '-' . $month . '.xlsx');
    }
}
