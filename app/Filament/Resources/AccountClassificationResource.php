<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountClassificationResource\Pages;
use App\Filament\Resources\AccountClassificationResource\RelationManagers;
use App\Models\AccountClassification;
use App\Models\AccountParent;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Expr\Cast\Object_;

class AccountClassificationResource extends Resource
{
    protected static ?string $model = AccountClassification::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Kelola Klasifikasi Akun';
    protected static ?string $pluralModelLabel = 'Kelola Klasifikasi Akun';

    protected static ?string $navigationGroup = 'Manajemen Akun';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->label('Akun Utama')
                    ->options(
                        AccountParent::all()->mapWithKeys(function ($item) {
                            return [$item->id => $item->parent_name];
                        })
                    )
                    ->placeholder('Pilih Akun Utama')
                    ->required(),
                TextInput::make('classification_name')
                    ->label('Nama Klasifikasi Akun')
                    ->autofocus()
                    ->placeholder('Masukkan Nama Klasifikasi Akun')
                    ->required(),
                TextInput::make('classification_code')
                    ->label('Kode Klasifikasi Akun')
                    ->autofocus()
                    ->placeholder('Masukkan Kode Klasifikasi Akun')
                    ->required()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('parent.parent_name')
                    ->label('Akun Utama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classification_name')
                    ->label('Nama Klasifikasi Akun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classification_code')
                    ->label('Kode Klasifikasi Akun')
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
            'index' => Pages\ManageAccountClassifications::route('/'),
        ];
    }
}
