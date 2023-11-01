<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\GeneralJournalResource;
use App\Models\JournalDetail;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTransactions extends BaseWidget
{
    protected int | string | array $columnSpan = '1';

    protected static ?int $sort = 2;

    protected static ?string $heading = 'Transaksi Tebaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(GeneralJournalResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('receipt')
                    ->label('Kwitansi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    //i want to format the date like 1 november 2021
                    ->date('d F Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Action::make('Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn (JournalDetail $record): string => GeneralJournalResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
