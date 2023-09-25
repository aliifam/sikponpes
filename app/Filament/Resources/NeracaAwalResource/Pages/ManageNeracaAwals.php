<?php

namespace App\Filament\Resources\NeracaAwalResource\Pages;

use App\Filament\Resources\NeracaAwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Alignment;

class ManageNeracaAwals extends ManageRecords
{
    protected static string $resource = NeracaAwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Neraca Awal')
                ->modalWidth('xl')
                ->modalAlignment(Alignment::Center)
                ->createAnother(false),
        ];
    }
}
