<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Phpsa\FilamentPasswordReveal\Password;

class Login extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login';

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

    public function forgotPasswordAction(): Action
    {
        return Action::make('requestPasswordReset')
            ->link()
            ->label(__('Lupa password?'))
            ->url(filament()->getRequestPasswordResetUrl());
    }
}
