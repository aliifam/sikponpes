<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class NeracaJalur extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static string $view = 'filament.pages.neraca-jalur';

    protected static ?string $navigationGroup = 'Manajemen Neraca';

    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
}
