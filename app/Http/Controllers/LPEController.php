<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;

class LPEController extends Controller
{
    public function exportpdf(Request $request)
    {
        $encrypted = Crypt::decrypt($request->document);
        // dd($encrypted);
        $year = $encrypted['year'];
        $month = $encrypted['month'];
        $session = $encrypted['id'];

        // dd($year, $month, $session);


    }

    public function exportexcel(Request $request)
    {
    }
}
