<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Phpsa\FilamentPasswordReveal\Password;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                Password::make('password')
                    ->label('Password')
                    ->required(),
                $this->getRememberFormComponent(),
            ]);
    }
}
