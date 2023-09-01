<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use App\Models\AccountParent;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Manajemen Akun';

    protected static ?string $navigationLabel = 'Kelola Akun';
    protected static ?string $pluralModelLabel = 'Kelola Akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->label('Akun Utama')
                    ->reactive()
                    ->options(
                        AccountParent::all()->pluck('parent_name', 'id')
                    )
                    ->placeholder('Pilih Akun Utama')
                    ->afterStateUpdated(function (callable $set) {
                        $set('classification_id', null);
                    })
                    ->required(),
                Select::make('classification_id')
                    ->label('Klasifikasi Akun')
                    ->options(
                        function (callable $get) {
                            $akunutama = AccountParent::find($get('parent_id'));
                            if ($akunutama) {
                                return $akunutama->classification->pluck('classification_name', 'id');
                            }
                            return [];
                        }
                    ),
                TextInput::make('account_name')
                    ->label('Nama Akun')
                    ->autofocus()
                    ->placeholder('Masukkan Nama Akun')
                    ->required(),
                TextInput::make('account_code')
                    ->label('Kode Akun')
                    ->autofocus()
                    ->placeholder('Masukkan Kode Akun')
                    ->required(),
                Radio::make('account_type')
                    ->label('Tipe Akun')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'Kredit',
                    ])
                    ->default('debit')
                    ->required(),
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
            'index' => Pages\ManageAccounts::route('/'),
        ];
    }
}
