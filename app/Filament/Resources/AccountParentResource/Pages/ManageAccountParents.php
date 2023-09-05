<?php

namespace App\Filament\Resources\AccountParentResource\Pages;

use App\Filament\Resources\AccountParentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Alignment;

class ManageAccountParents extends ManageRecords
{
    protected static string $resource = AccountParentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('xl')
                ->modalAlignment(Alignment::Center)
                ->createAnother(false),
        ];
    }
}
