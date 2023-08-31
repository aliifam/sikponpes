<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountParentResource\Pages;
use App\Filament\Resources\AccountParentResource\RelationManagers;
use App\Models\AccountParent;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;

class AccountParentResource extends Resource
{
    protected static ?string $model = AccountParent::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Kelola Akun Utama';
    protected static ?string $pluralModelLabel = 'Kelola Akun Utama';

    protected static ?string $navigationGroup = 'Manajemen Akun';

    public static array $akunUtama = [
        [
            "id" => 1,
            "name" => "Aset",
            "code" => "1",
        ],
        [
            "id" => 2,
            "name" => "Liabilitas",
            "code" => "2",
        ],
        [
            "id" => 3,
            "name" => "Ekuitas",
            "code" => "3",
        ],
        [
            "id" => 4,
            "name" => "Pendapatan",
            "code" => "4",
        ],
        [
            "id" => 5,
            "name" => "Biaya",
            "code" => "5",
        ],

    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pesantren_id')
                    ->label('Pondok Pesantren')
                    ->searchable()
                    ->required()
                    ->options(
                        \App\Models\Pesantren::all()->pluck('name', 'id')
                    ),
                Select::make('parent_name')
                    ->label('Nama Akun Utama')
                    ->searchable()
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('parent_code', collect(self::$akunUtama)->where('name', $state)->first()['code']);
                    })
                    ->options(
                        //wheren not yet in database
                        collect(self::$akunUtama)->whereNotIn('name', AccountParent::all()->pluck('parent_name'))->pluck('name', 'name')
                    ),
                Hidden::make('parent_code')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pesantren.name')
                    ->label('Pondok Pesantren')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent_name')
                    ->label('Nama Akun Utama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent_code')
                    ->label('Kode Akun Utama')
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
            'index' => Pages\ManageAccountParents::route('/'),
        ];
    }
}
