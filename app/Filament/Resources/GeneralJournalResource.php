<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralJournalResource\Pages;
use App\Filament\Resources\GeneralJournalResource\RelationManagers;
use App\Models\Account;
use App\Models\GeneralJournal;
use App\Models\JournalDetail;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Dompdf\FrameDecorator\Text;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class GeneralJournalResource extends Resource
{
    protected static ?string $model = JournalDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Jurnal Umum';
    protected static ?string $pluralModelLabel = 'Jurnal Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('receipt')
                    ->label('Kwitansi')
                    ->placeholder('Masukkan Kwitansi')
                    ->prefixIcon('heroicon-o-calculator')
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->placeholder('Masukkan Tanggal')
                    ->prefixIcon('heroicon-o-calendar')
                    ->native(false)
                    ->closeOnDateSelection()
                    ->required(),
                Textarea::make('description')
                    ->label('Keterangan')
                    ->placeholder('Masukkan Keterangan')
                    ->required(),
                Hidden::make('pesantren_id')
                    ->default(Filament::getTenant()->id),
                TableRepeater::make('journals')
                    ->hideLabels()
                    ->grid(2)
                    ->minItems(2)
                    ->schema([
                        Select::make('account_id')
                            ->label('Nama Akun')
                            ->placeholder('Pilih Akun')
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
                            ->disableOptionWhen(function ($value, $state, Get $get) {
                                return collect($get('../*.account_id'))->contains($value);
                            })
                            ->required(),
                        Select::make('position')
                            ->label('Posisi')
                            ->placeholder('Pilih Posisi')
                            ->options([
                                'debit' => 'Debit',
                                'credit' => 'Kredit',
                            ])
                            ->required(),
                        TextInput::make('amount')
                            ->label('amount')
                            ->numeric()
                            ->prefix('Rp. ')
                            ->placeholder('Masukkan Jumlah ')
                            ->required(),
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->filters([
                // Filter::make('date')
                //     ->form([
                //         DatePicker::make('created_from'),
                //         DatePicker::make('created_until'),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['created_from'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                //             )
                //             ->when(
                //                 $data['created_until'],
                //                 fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                //             );
                //     }),
                DateRangeFilter::make('date')
                    ->label('Pilih rentang tanggal')
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListGeneralJournals::route('/'),
            'create' => Pages\CreateGeneralJournal::route('/create'),
            'edit' => Pages\EditGeneralJournal::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // ...
            ]);
    }
}
