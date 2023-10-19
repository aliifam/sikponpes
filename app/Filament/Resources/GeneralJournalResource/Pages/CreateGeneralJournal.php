<?php

namespace App\Filament\Resources\GeneralJournalResource\Pages;

use App\Filament\Resources\GeneralJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGeneralJournal extends CreateRecord
{
    protected static string $resource = GeneralJournalResource::class;

    protected static bool $canCreateAnother = false;

    //change title
    public static ?string $title = 'Tambah Jurnal';
}
