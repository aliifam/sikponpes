<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountOverview extends BaseWidget
{
    protected function getStats(): array
    {

        return [
            //using Account Model wheren pesantren_id = now pesantren
            Stat::make('Total Akun', Account::where('pesantren_id', Filament::getTenant()->id)->count())
                ->descriptionIcon('heroicon-o-users'),
            Stat::make('Saldo Kas', 'Rp. 200.000.000')
                ->descriptionIcon('heroicon-o-currency-dollar'),
            Stat::make('Transaksi', '21')
                ->descriptionIcon('heroicon-o-cash'),
            // Stat::make('Laba Rugi', 'Rp. 20.000.000')
            //     ->descriptionIcon('heroicon-o-cash'),
        ];
    }
}
