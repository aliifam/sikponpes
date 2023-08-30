<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesantrenResource\Pages;
use App\Filament\Resources\PesantrenResource\RelationManagers;
use App\Models\Pesantren;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
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
                Section::make('')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pesantren')
                            ->autofocus()
                            ->required()
                            ->placeholder('Nama Pesantren'),
                        Textarea::make('address')
                            ->label('Alamat Pesantren')
                            ->autofocus()
                            ->required()
                            ->placeholder('Alamat Pesantren'),
                        TextInput::make('phone_number')
                            ->autofocus()
                            ->required()
                            ->placeholder('Nomor Telepon Pesantren'),
                        Toggle::make('is_active')
                            ->label('Status Aktif'),
                        Hidden::make('user_id')
                            ->default(auth()->user()->id)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pesantren')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('Alamat Pesantren')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->label('Nomor Telepon Pesantren')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Status Aktif')
                    ->sortable(),
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
