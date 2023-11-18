<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Filament\Resources\SantriResource\RelationManagers;
use App\Models\Santri;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Nama Santri')),
                TextInput::make('nis')
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('NIS Santri')),
                Textarea::make('alamat')
                    ->maxLength(255)
                    ->placeholder(__('Alamat Santri')),
                TextInput::make('telepon')
                    ->maxLength(255)
                    ->placeholder(__('Telepon Santri')),
                TextInput::make('email')
                    ->maxLength(255)
                    ->placeholder(__('Email Santri')),
                Select::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->placeholder(__('Jenis Kelamin Santri')),
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
            'index' => Pages\ManageSantris::route('/'),
        ];
    }
}
