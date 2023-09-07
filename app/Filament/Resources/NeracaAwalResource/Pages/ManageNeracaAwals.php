<?php

namespace App\Filament\Resources\NeracaAwalResource\Pages;

use App\Filament\Resources\NeracaAwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNeracaAwals extends ManageRecords
{
    protected static string $resource = NeracaAwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
