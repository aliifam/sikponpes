<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Phpsa\FilamentPasswordReveal\Password;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                Password::make('password')
                    ->label('Password')
                    ->required()
                    ->rule(RulesPassword::default())
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->same('password_confirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
                Password::make('password_confirmation')
                    ->label('Konfirmasi Password')
                    ->dehydrated(false)
                    ->required(),
                // $this->getPasswordConfirmationFormComponent()
            ]);
    }

    // public function getRegisterFormAction(): Action
    // {
    //     return Action::make('register')
    //         ->label('Register')
    //         //visibility false
    //         ->visible(false);
    // }
}
