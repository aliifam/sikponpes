<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Pesantren;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;

class RegisterPesantren extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Daftarkan Pesantren';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Pesantren')
                    ->autofocus()
                    ->required()
                    ->placeholder('Nama Pesantren'),
                Textarea::make('address')
                    ->label('Alamat Pesantren')
                    ->autofocus()
                    ->required()
                    ->placeholder('Alamat Pesantren'),
                TextInput::make('phone_number')
                    ->autofocus()
                    ->required()
                    ->placeholder('Nomor Telepon Pesantren'),
                Toggle::make('is_active')
                    ->label('Status Aktif'),
                Hidden::make('user_id')
                    ->default(auth()->user()->id)
            ]);
    }

    protected function handleRegistration(array $data): Pesantren
    {
        $team = Pesantren::create($data);

        $team->user()->attach(auth()->user());

        $defaultAccountClassificationData = [
            [
                'pesantren_id' => $team->id,
                'parent_id' => 1,
                'classification_code' => '1.1',
                'classification_name' => 'Kas',
            ],
            [
                'pesantren_id' => $team->id,
                'classification_id' => 1,
            ]
        ];

        return $team;
    }
}
