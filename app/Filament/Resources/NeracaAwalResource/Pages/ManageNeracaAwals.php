<?php

namespace App\Filament\Resources\NeracaAwalResource\Pages;

use App\Filament\Resources\NeracaAwalResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\View\View;

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

    public function getHeader(): ?View
    {
        $data = Actions\CreateAction::make()
            ->modalHeading('Tambah Neraca Awal')
            ->modalWidth('xl')
            ->modalAlignment(Alignment::Center)
            ->createAnother(false);

        $years = \App\Models\InitialBalance::whereHas('pesantren', function ($query) {
            $query->where('id', Filament::getTenant()->id);
        })->selectRaw('YEAR(date) as year')->distinct()->get()->pluck('year');

        return view('filament.custom.neraca-awal.header', compact('data', 'years'));
    }

    public function getFooter(): ?View
    {
        $totalDebit = \App\Models\InitialBalance::whereHas('pesantren', function ($query) {
            $query->where('id', Filament::getTenant()->id);
        })->whereHas('account', function ($query) {
            $query->where('position', 'Debit');
        })->sum('amount');

        $totalKredit = \App\Models\InitialBalance::whereHas('pesantren', function ($query) {
            $query->where('id', Filament::getTenant()->id);
        })->whereHas('account', function ($query) {
            $query->where('position', 'Kredit');
        })->sum('amount');

        $status = $totalDebit == $totalKredit ? 'Balance' : 'Unbalance';

        return view('filament.custom.neraca-awal.footer');
    }
}
