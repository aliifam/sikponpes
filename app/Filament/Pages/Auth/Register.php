<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Account')
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                    Step::make('Pesantren')
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
                        ]),
                ])
            ])->action('/register');
    }
}
