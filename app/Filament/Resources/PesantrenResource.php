<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesantrenResource\Pages;
use App\Filament\Resources\PesantrenResource\RelationManagers;
use App\Models\Pesantren;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesantrenResource extends Resource
{
    protected static ?string $model = Pesantren::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Kelola Pesantren';
    protected static ?string $pluralModelLabel = 'Kelola Pesantren';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesantrens::route('/'),
            'create' => Pages\CreatePesantren::route('/create'),
            'edit' => Pages\EditPesantren::route('/{record}/edit'),
        ];
    }
}
