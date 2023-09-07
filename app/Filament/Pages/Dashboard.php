<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Customize properties or methods here
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';

    public function getTitle(): string
    {
        $pesantren = Filament::getTenant()->name;
        return "Dashboard {$pesantren}";
    }
}
