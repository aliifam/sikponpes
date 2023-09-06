<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use App\Models\AccountParent;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
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
                        //where pesantren_id = pesantren_id
                        //label = parent_code + parent_name
                        //value = id
                        AccountParent::where('pesantren_id', Filament::getTenant()->id)
                            ->get()
                            ->mapWithKeys(
                                function ($item) {
                                    return [$item['id'] => $item['parent_code'] . ' - ' . $item['parent_name']];
                                }
                            )
                    )
                    ->searchable()
                    ->placeholder('Pilih Akun Utama')
                    ->afterStateUpdated(function (callable $set) {
                        $set('classification_id', null);
                    })
                    ->required(),
                Select::make('classification_id')
                    ->label('Klasifikasi Akun')
                    ->required()
                    ->options(
                        //where pesantren_id = pesantren_id and parent_id = parent_id
                        //label = classification_code + classification_name
                        //value = id
                        function (callable $get) {
                            $parent = AccountParent::find($get('parent_id'));
                            if (!$parent) {
                                return [];
                            }
                            return $parent->classification()
                                ->get()
                                ->mapWithKeys(
                                    function ($item) {
                                        return [$item['id'] => $item['classification_code'] . ' - ' . $item['classification_name']];
                                    }
                                );
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
                Radio::make('position')
                    ->label('Tipe Akun')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'Kredit',
                    ])
                    ->default('debit')
                    ->required(),
                Hidden::make('pesantren_id')
                    ->default(Filament::getTenant()->id)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('classification.classification_name')
                    ->label('Klasifikasi')
                    ->orderQueryUsing(function (Builder $query, string $direction) {
                        //covert account_code to interger before ordering to avoid ordering 1, 10, 11, 2, 3, 4, 5, 6, 7, 8, 9
                        $query->orderByRaw('CAST(account_code AS UNSIGNED) ' . $direction);
                    })
                    ->collapsible(),
                // Group::make('classification.parent.parent_name')
                //     ->label('Parent')
                //     ->collapsible()
                //     ->collapsible(),
            ])
            ->defaultGroup('classification.classification_name')
            ->paginated(false)
            ->columns([
                TextColumn::make('classification.parent.parent_name')
                    ->label('Parent')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('classification.classification_name')
                //     ->label('Klasifikasi Akun')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('account_name')
                    ->label('Nama Akun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('account_code')
                    ->label('Kode Akun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Tipe Akun')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // SelectFilter::make('parent_name')
                //     ->label('Parent')
                //     ->options(
                //         AccountParent::where('pesantren_id', Filament::getTenant()->id)
                //             ->get()
                //             ->mapWithKeys(
                //                 function ($item) {
                //                     return [$item['id'] => $item['parent_code'] . ' - ' . $item['parent_name']];
                //                 }
                //             )
                //     )
                //     ->placeholder('Pilih Parent')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Akun')
                    ->fillForm(function ($record) {
                        return [
                            'parent_id' => $record->classification->parent_id,
                            'classification_id' => $record->classification_id,
                            'account_name' => $record->account_name,
                            'account_code' => $record->account_code,
                            'position' => $record->position,
                        ];
                    }),
                Tables\Actions\DeleteAction::make()
                    //anda yakin hapus "$record->account_name" ?
                    ->modalHeading(fn ($record) => 'Anda yakin hapus "' . $record->account_name . '" ?')
                    ->modalDescription('Are you sure you\'d like to delete this post? This cannot be undone.')
                    ->requiresConfirmation(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
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
