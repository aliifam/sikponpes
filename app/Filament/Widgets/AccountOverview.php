<?php

namespace App\Filament\Widgets;

use App\Models\Account;
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
        $session = Filament::getTenant()->id;

        //count journal detail
        $transactions = JournalDetail::where('pesantren_id', $session)->count();

        //count saldo kas
        $kas_id = Account::where('pesantren_id', $session)->where('account_name', 'Kas')->first()->id;
        $saldo_kas = InitialBalance::where('pesantren_id', $session)->where('account_id', $kas_id)->whereYear('date', $year)->first();
        $saldo_kas = $saldo_kas->amount;
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

        return [
            //using Account Model wheren pesantren_id = now pesantren
            Stat::make('Total Akun', Account::where('pesantren_id', Filament::getTenant()->id)->count())
                ->descriptionIcon('heroicon-o-users'),
            Stat::make('Saldo Kas', $saldo_kas)
                ->descriptionIcon('heroicon-o-currency-dollar'),
            Stat::make('Transaksi', $transactions)
                ->descriptionIcon('heroicon-o-cash'),
            // Stat::make('Laba Rugi', 'Rp. 20.000.000')
            //     ->descriptionIcon('heroicon-o-cash'),
        ];
    }
}
