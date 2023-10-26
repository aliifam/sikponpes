<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;

class NeracaSaldoController extends Controller
{
    public function export($year, $month = null)
    {
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
    }
}
