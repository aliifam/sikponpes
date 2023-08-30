<?php

namespace App\Filament\Resources\AccountClassificationResource\Pages;

use App\Filament\Resources\AccountClassificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAccountClassifications extends ManageRecords
{
    protected static string $resource = AccountClassificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
