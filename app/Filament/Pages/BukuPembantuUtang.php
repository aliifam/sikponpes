<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class BukuPembantuUtang extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.pages.buku-pembantu-utang';

    protected static ?string $navigationGroup = 'Manajemen Buku';
}
