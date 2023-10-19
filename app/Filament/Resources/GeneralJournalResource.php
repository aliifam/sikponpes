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
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                TextInput::make('kwitansi')
                    ->label('receipt')
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
                    ->minItems(1)
                    ->withoutHeader()
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
                            ->required(),
                        Select::make('position')
                            ->label('Posisi')
                            ->placeholder('Pilih Posisi')
                            ->options([
                                'debit' => 'Debit',
                                'kredit' => 'Kredit',
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make(),
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
