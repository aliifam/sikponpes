<?php

namespace App\Filament\Resources\PesantrenResource\Pages;

use App\Filament\Resources\PesantrenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPesantren extends EditRecord
{
    protected static string $resource = PesantrenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
