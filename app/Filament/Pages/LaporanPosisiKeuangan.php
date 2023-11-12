<?php

namespace App\Filament\Pages;

use App\Models\AccountParent;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Crypt;

class LaporanPosisiKeuangan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.pages.laporan-posisi-keuangan';
    protected static ?string $navigationGroup = 'Manajemen Laporan';

    public $session;
    public $years;
    public $year;
    public $month;
    public $assetData;
    public $liabilityData;
    public $equityData;
    public $asset;
    public $liability;
    public $equity;
    public $aktiva;
    public $pasiva;
    public $endpoint;
    public $surpdef;

    public function mount()
    {
        if (isset($_GET['year'])) {
            $year = $_GET['year'];
            $month = $_GET['month'];
        } else {
            $year = date('Y');
            $month = date('m');
        }
        $session = Filament::getTenant()->id;

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

        //tambahan kak adlina minta ekuitas di pasiva jadinya ambil dari lpe yang ekuitas akhir

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

        $pasiva = $pasiva + $surpdef;

        //end tambahan kak adlina

        // dd($assetData);
        // dd($liabilityData);
        $this->years = $years;
        $this->year = $year;
        $this->month = $month;
        $this->session = $session;
        $this->assetData = $assetData;
        $this->liabilityData = $liabilityData;
        $this->equityData = $equityData;
        $this->asset = $asset;
        $this->liability = $liability;
        $this->equity = $equity;
        $this->aktiva = $aktiva;
        $this->pasiva = $pasiva;
        $this->endpoint = Crypt::encrypt(['year' => $year, 'month' => $month, 'id' => $session]);
        $this->surpdef = $surpdef;
    }
}
