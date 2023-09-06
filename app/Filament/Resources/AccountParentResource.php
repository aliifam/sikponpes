<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountParentResource\Pages;
use App\Filament\Resources\AccountParentResource\RelationManagers;
use App\Models\AccountParent;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccountParentResource extends Resource
{
    protected static ?string $model = AccountParent::class;

    protected static ?string $tenantRelationshipName = 'parents';

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Kelola Parent Akun';
    protected static ?string $pluralModelLabel = 'Kelola Parent Akun';

    protected static ?string $navigationGroup = 'Manajemen Akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('parent_name')
                    ->label('Nama Akun Utama')
                    ->autofocus()
                    ->placeholder('Masukkan Nama Akun Utama')
                    ->required(),
                TextInput::make('parent_code')
                    ->label('Kode Akun Utama')
                    ->autofocus()
                    ->placeholder('Masukkan Kode Akun Utama')
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('parent_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent_name')
                    ->label('Nama')
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
            'index' => Pages\ManageAccountParents::route('/'),
        ];
    }
}
