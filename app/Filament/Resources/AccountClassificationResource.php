<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountClassificationResource\Pages;
use App\Filament\Resources\AccountClassificationResource\RelationManagers;
use App\Models\AccountClassification;
use App\Models\AccountParent;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AccountClassificationResource extends Resource
{
    protected static ?string $model = AccountClassification::class;

    protected static ?string $tenantRelationshipName = 'classifications';

    protected static ?string $tenantOwnershipRelationshipName = 'pesantren';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Kelola Klasifikasi Akun';
    protected static ?string $pluralModelLabel = 'Kelola Klasifikasi Akun';

    protected static ?string $navigationGroup = 'Manajemen Akun';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->label('Akun Parent')
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
                    ->placeholder('Pilih Akun Utama')
                    ->searchable()
                    ->required(),
                TextInput::make('classification_name')
                    ->label('Nama Klasifikasi Akun')
                    ->autofocus()
                    ->placeholder('Masukkan Nama Klasifikasi Akun')
                    ->required(),
                TextInput::make('classification_code')
                    ->label('Kode Klasifikasi Akun')
                    ->placeholder('Masukkan Kode Klasifikasi Akun')
                    ->required(),
                Hidden::make('pesantren_id')
                    ->default(Filament::getTenant()->id)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('parent.parent_name',)
                    ->label('Akun Utama')
                    //order by parent code
                    ->orderQueryUsing(function (Builder $query, string $direction) {
                        $query->orderBy('classification_code', $direction);
                    })
                    ->collapsible()
            ])
            ->defaultGroup('parent.parent_name')
            ->defaultSort('classification_code', 'asc')
            ->paginated(false)
            ->columns([
                // TextColumn::make('parent.parent_name')
                //     ->label('Akun Utama')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('classification_name')
                    ->label('Nama Klasifikasi Akun')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classification_code')
                    ->label('Kode Klasifikasi Akun')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('classification_code', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageAccountClassifications::route('/'),
        ];
    }
}
