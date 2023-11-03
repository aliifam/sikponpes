<?php

namespace App\Filament\Pages;

use App\Models\AccountParent;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class LaporanPerubahanEkuitas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.laporan-perubahan-ekuitas';

    protected static ?string $navigationGroup = 'Manajemen Laporan';

    public $session;
    public $years;
    public $year;
    public $month;
    public $saldo_berjalan;
    public $equityData;

    public function mount()
    {
        $session = Filament::getTenant()->id;

        if (isset($_GET['year'], $_GET['month'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }

        $parent = AccountParent::with('classification.account')->where('pesantren_id', $session)->get();
        $saldo_berjalan = 0;

        foreach ($parent as $p) {
            foreach ($p->classification as $c) {
                $i = 0;
                foreach ($c->account as $a) {
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
                    $i++;
                    if ($p->parent_name == "Ekuitas") {
                        $equityArray[$i]['name'] = $a->account_name;
                        $equityArray[$i]['code'] = $a->account_code;
                        $equityArray[$i]['ending balance'] = $endingBalance;
                    }
                    if ($p->parent_name == "Pendapatan") {
                        if ($position == "kredit") {
                            $saldo_berjalan += $endingBalance;
                        } else {
                            $saldo_berjalan -= $endingBalance;
                        }
                    } else if ($p->parent_name == "Biaya") {
                        if ($position == "debit") {
                            $saldo_berjalan -= $endingBalance;
                        } else {
                            $saldo_berjalan += $endingBalance;
                        }
                    }
                }
            }
        }

        //

        // dd($equityArray, $saldo_berjalan);
        // dd($saldo_berjalan);

        $years = JournalDetail::selectRaw('YEAR(date) as year')
            ->whereHas('general_journal.account.classification.parent', function ($q) use ($session) {
                $q->where('pesantren_id', $session);
            })->union(InitialBalance::selectRaw('YEAR(date) as year')
                ->whereHas('account.classification.parent', function ($q) use ($session) {
                    $q->where('pesantren_id', $session);
                }))->orderBy('year', 'DESC')->get();

        if ($years->isEmpty()) {
            //same data structure as $years
            $years = collect([
                (object) [
                    'year' => date('Y')
                ]
            ]);
        }

        $this->session = $session;
        $this->years = $years;
        $this->year = $year;
        $this->month = $month;
        $this->saldo_berjalan = $saldo_berjalan;
        $this->equityData = $equityArray;
    }
}
