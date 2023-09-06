<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AccountOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //using Account Model
            Stat::make('Total Akun', Account::count())
                ->description('Total Akun pada sistem pesantren')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),
        ];
    }
}
