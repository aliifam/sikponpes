<?php

namespace App\Filament\Resources\PesantrenResource\Pages;

use App\Filament\Resources\PesantrenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPesantrens extends ListRecords
{
    protected static string $resource = PesantrenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
