<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeracaAwalResource\Pages;
use App\Filament\Resources\NeracaAwalResource\RelationManagers;
use App\Models\Account;
use App\Models\InitialBalance;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use Filament\Forms\Components\Hidden;

class NeracaAwalResource extends Resource
{
    protected static ?string $model = InitialBalance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->placeholder('Masukkan Tanggal')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->required(),
                Select::make("account_id")
                    ->label("Nama Akun")
                    ->placeholder("Pilih Akun")
                    ->searchable()
                    ->options(
                        Account::where('pesantren_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(
                                function ($item) {
                                    return [$item['id'] => $item['account_code'] . ' - ' . $item['account_name']];
                                }
                            )
                    )
                    ->required(),
                TextInput::make('amount')
                    ->label('Jumlah')
                    ->numeric()
                    ->prefix('Rp. ')
                    ->placeholder('Masukkan Jumlah ')
                    ->required(),
                Hidden::make('pesantren_id')
                    ->default(Filament::getTenant()->id)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.account_code')
                    ->label('Kode Akun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.account_name')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
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
            'index' => Pages\ManageNeracaAwals::route('/'),
        ];
    }
}
