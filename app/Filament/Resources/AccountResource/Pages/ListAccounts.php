<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\AccountParent;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Akun Parent')
                ->modalWidth('xl')
                ->modalAlignment(Alignment::Center)
                ->createAnother(false),
            //auto select and fill current data

        ];
    }

    // public function getTabs(): array
    // {
    //     return [
    //         'all' => Tab::make(),
    //         'active' => Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
    //         'inactive' => Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false)),
    //     ];
    // }

    //create tab for filering account by parent
    public function getTabs(): array
    {
        $akunParent = AccountParent::where('pesantren_id', Filament::getTenant()->id)->get();

        $tabs = [];
        foreach ($akunParent as $key => $value) {
            $tabs[$value->id] = Tab::make()
                ->label($value->parent_code . '. ' . $value->parent_name)
                //parent > classification > account
                //first query to classification table to get classification_id from parent table (parent_id) after that query to account table to get account_id from classification table (classification_id)
                ->modifyQueryUsing(function (Builder $query) use ($value) {
                    $query->whereHas('classification', function (Builder $query) use ($value) {
                        $query->where('parent_id', $value->id);
                    });
                });
        }
        return $tabs;
    }
}
