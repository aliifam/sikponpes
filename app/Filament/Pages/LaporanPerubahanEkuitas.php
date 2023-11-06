<?php

namespace App\Filament\Pages;

use App\Models\Account;
use App\Models\AccountParent;
use App\Models\GeneralJournal;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class LaporanPerubahanEkuitas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.laporan-perubahan-ekuitas';

    protected static ?string $navigationGroup = 'Manajemen Laporan';

    //laporan perubahan ekuitas
    // modalawal = initial balance akun ekuitas
    // setoran modal = ambil jurnal yang akunnya ekuitas bila posisi debit maka kurangi, bila posisi kredit maka tambah
    // saldo berjalan = saldo akun pendapatan - saldo akun biaya


    public $session;
    public $years;
    public $year;
    public $month;
    public $saldo_berjalan;
    public $setoran_modal;
    public $modal_awal;
    public $prive;
    public $surpdef;

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

        $modal_awal = Account::where('pesantren_id', $session)->where('account_name', 'Ekuitas')->first();

        if ($modal_awal) {
            $modal_awal = $modal_awal->initialBalance()->whereYear('date', $year)->first();
            $modal_awal = $modal_awal ? $modal_awal->amount : 0;
        } else {
            $modal_awal = 0;
        }

        // dd($modal_awal);

        $ekuitas_id = Account::where('pesantren_id', $session)->where('account_name', 'Ekuitas')->first()->id;
        $prive_id = Account::where('pesantren_id', $session)->where('account_name', 'Prive')->first()->id;

        // dd($ekuitas_id, $prive_id);

        $setoran_modal = 0;

        // journal detail has many general journal and general journal belongs to journal detail,  I want to get list of general journal in $year but the date is in journal detail table
        $jurnal_yg_ada_ekuitasnya = GeneralJournal::whereHas('detail', function ($q) use ($year) {
            $q->whereYear('date', $year);
        })->where('pesantren_id', $session)->where('account_id', $ekuitas_id)->get();

        // dd($jurnal_yg_ada_ekuitasnya->toArray());

        foreach ($jurnal_yg_ada_ekuitasnya as $jurnal) {
            if ($jurnal->position == 'debit') {
                $setoran_modal -= $jurnal->amount;
            } else if ($jurnal->position == 'credit') {
                $setoran_modal += $jurnal->amount;
            }
        }

        $prive_awal = Account::where('pesantren_id', $session)->where('account_name', 'Prive')->first();

        if ($prive_awal) {
            $prive_awal = $prive_awal->initialBalance()->whereYear('date', $year)->first();
            $prive_awal = $prive_awal ? $prive_awal->amount : 0;
        } else {
            $prive_awal = 0;
        }

        $jurnal_yg_ada_privenya = GeneralJournal::whereHas('detail', function ($q) use ($year) {
            $q->whereYear('date', $year);
        })->where('pesantren_id', $session)->where('account_id', $prive_id)->get();

        foreach ($jurnal_yg_ada_privenya as $jurnal) {
            if ($jurnal->position == 'debit') {
                $prive_awal -= $jurnal->amount;
            } else if ($jurnal->position == 'credit') {
                $prive_awal += $jurnal->amount;
            }
        }


        $surpdef = 0;
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

        $surpdef = $income - $expense;

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
        $this->modal_awal = $modal_awal;
        $this->setoran_modal = $setoran_modal;
        $this->prive = $prive_awal;
        $this->surpdef = $surpdef;
    }
}
