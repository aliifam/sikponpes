<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanAktifitasController extends Controller
{
    public function exportpdf(Request $request)
    {
        $pesantren = $request->id;
        $year = $request->year;
        $month = $request->month;
    }

    public function exportexcel(Request $request)
    {
    }
}
