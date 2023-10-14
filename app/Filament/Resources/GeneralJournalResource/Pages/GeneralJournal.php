<?php

namespace App\Filament\Resources\GeneralJournalResource\Pages;

use App\Filament\Resources\GeneralJournalResource;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class GeneralJournal extends Page
{
    protected static string $resource = GeneralJournalResource::class;

    protected static string $view = 'filament.resources.general-journal-resource.pages.general-journal';

    //change title
    public function getTitle(): string|Htmlable
    {
        return 'Jurnal Umum';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Manajemen Jurnal Umum Pesantren';
    }
}
