<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LPKController extends Controller
{
    public function exportpdf(Request $request)
    {
        $encrypted = Crypt::decrypt($request->document);
        dd($encrypted);
    }

    public function exportexcel(Request $request)
    {
    }
}
