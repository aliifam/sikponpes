<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Database\Eloquent\Model;

class EditPesantrenProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Profil Pesantren';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Pesantren')
                    ->required(),
                Textarea::make('address')
                    ->label('Deskripsi Pesantren')
                    ->required(),
                TextInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true)
            ]);
    }
}
