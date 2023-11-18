<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerusahaanResource\Pages;
use App\Filament\Resources\PerusahaanResource\RelationManagers;
use App\Models\Perusahaan;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerusahaanResource extends Resource
{
    protected static ?string $model = Perusahaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Nama Perusahaan')),
                Textarea::make('alamat')
                    ->maxLength(255)
                    ->placeholder(__('Alamat Perusahaan')),
                TextInput::make('telepon')
                    ->maxLength(255)
                    ->placeholder(__('Telepon Perusahaan')),
                TextInput::make('email')
                    ->maxLength(255)
                    ->placeholder(__('Email Perusahaan')),
                Hidden::make('pesantren_id')
                    ->default(Filament::getTenant()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePerusahaans::route('/'),
        ];
    }
}
