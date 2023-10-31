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
use Filament\Forms\Get;
use stdClass;

class NeracaAwalResource extends Resource
{
    protected static ?string $model = InitialBalance::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Neraca Awal';
    protected static ?string $pluralModelLabel = 'Neraca Awal';

    protected static ?string $navigationGroup = 'Manajemen Neraca';

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
                        //account where not in initial balance in the same year and pesantren_id
                        Account::where('pesantren_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(
                                function ($item) {
                                    return [$item['id'] => $item['account_code'] . ' - ' . $item['account_name']];
                                }
                            )
                    )
                    //if the account already exist in the same year, disable the option, if value is already exist in the same year, disable the option
                    // ->disableOptionWhen(function ($value, Get $get) {
                    //     return InitialBalance::where('pesantren_id', Filament::getTenant()->id)
                    // })
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

    public Account $account;

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->defaultGroup('account.classification.parent.parent_name')
            ->columns([
                Tables\Columns\TextColumn::make('account.account_code')
                    ->label('Kode Akun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.account_name')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.position')
                    ->label('Posisi')
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
            'index' => Pages\NeracaAwal::route('/'),
            'create' => Pages\CreateNeracaAwal::route('/create'),
            'edit' => Pages\EditNeracaAwal::route('/{record}/edit')
            // 'index' => Pages\ManageNeracaAwals::route('/'),
        ];
    }

    //display only active initial balance by year
    // public static function getEloquentQuery(): Builder
    // {
    //     $latestYear = InitialBalance::whereHas('pesantren', function ($query) {
    //         $query->where('id', Filament::getTenant()->id);
    //     })->selectRaw('YEAR(date) as year')->distinct()->orderBy('year', 'desc')->first();

    //     return parent::getEloquentQuery()->whereHas('pesantren', function ($query) {
    //         $query->where('id', Filament::getTenant()->id);
    //     })->whereYear('date', $latestYear->year);
    // }
}
