<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\AccountParent;
use App\Models\GeneralJournal;
use App\Models\InitialBalance;
use App\Models\JournalDetail;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $year = date('Y');
        $month = date('m');
        $session = Filament::getTenant()->id;

        //count journal detail
        $transactions = JournalDetail::where('pesantren_id', $session)->count();

        //count saldo kas
        $kas_id = Account::where('pesantren_id', $session)->where('account_name', 'Kas')->first()->id;
        $saldo_kas = InitialBalance::where('pesantren_id', $session)->where('account_id', $kas_id)->whereYear('date', $year)->first();
        // $saldo_kas = $saldo_kas->amount;
        if ($saldo_kas) {
            $saldo_kas = $saldo_kas->amount;
        } else {
            $saldo_kas = 0;
        }

        $jurnal_yg_ada_kasnya = GeneralJournal::where('pesantren_id', $session)->where('account_id', $kas_id)->whereYear('created_at', $year)->get();

        // dd($jurnal_yg_ada_kasnya->toArray());
        // dd($saldo_kas->toArray());

        //foreach if debit add, if credit minus
        foreach ($jurnal_yg_ada_kasnya as $jurnal) {
            if ($jurnal->position == 'debit') {
                $saldo_kas += $jurnal->amount;
                // dd($saldo_kas);
            } else if ($jurnal->position == 'credit') {
                $saldo_kas -= $jurnal->amount;
            }
        }

        if ($saldo_kas == null) {
            $saldo_kas = 0;
        }

        // ekuitas
        $ekuitas = 0;
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

        $ekuitas = $income - $expense;

        $ekuitas = "Rp. " . strrev(implode('.', str_split(strrev(strval($ekuitas)), 3)));
        $saldo_kas = "Rp. " . strrev(implode('.', str_split(strrev(strval($saldo_kas)), 3)));

        return [
            //using Account Model wheren pesantren_id = now pesantren
            Stat::make('Total Ekuitas', $ekuitas)
                ->descriptionIcon('heroicon-o-cash'),
            Stat::make('Saldo Kas', $saldo_kas)
                ->descriptionIcon('heroicon-o-currency-dollar'),
            Stat::make('Jumlah Transaksi', $transactions)
                ->descriptionIcon('heroicon-o-cash'),
            Stat::make('Total Akun', Account::where('pesantren_id', Filament::getTenant()->id)->count())
                ->descriptionIcon('heroicon-o-users')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
