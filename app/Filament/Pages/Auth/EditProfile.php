<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use App\Models\User;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Http\Request;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;

class EditProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.auth.edit-profile';

    protected static bool $shouldRegisterNavigation = false;

    protected $record;

    public ?array $profileInformationData = []; 

    public ?array $passwordData = []; 

    public function mount(Request $request): void
    {
        $this->record = $request->user();

        $this->updateProfileInformationForm->fill($this->record->attributesToArray());

        $this->updatePasswordForm->fill($this->record->attributesToArray());
    }

    protected function updateProfileInformationForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label('Email address')
                    ->required()
                    ->email(),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone number')
                    ->required()
                    ->tel(),
            ])
            ->columns(2)
            ->statePath('profileInformationData');

    }

    protected function updatePasswordForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Current password')
                    ->required()
                    ->rule('current_password')
                    ->password()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('password')
                    ->label('New Password')
                    ->required()
                    ->password()
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->live(debounce: 300)
                    ->same('passwordConfirmation'),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->label('Confirm new password')
                    ->required()
                    ->password()
                    ->visible(fn (Get $get): bool => filled($get('password')))
                    ->dehydrated(false)
                ,
            ])
            ->columns(2)
            ->statePath('passwordData');

    }

    public function savePassword(): void
    {
        try {       
            auth()->user()->update($this->updatePasswordForm->getState());
        } catch (Halt $exception) {
            return;
        }

        Notification::make() 
            ->success()
            ->title(__('Password updated'))
            ->body(__('Password has been updated successful'))
            ->send(); 
    }

    public function saveProfileInformation(): void
    {
        try {        
            auth()->user()->update($this->updateProfileInformationForm->getState());
        } catch (Halt $exception) {
            return;
        }

        Notification::make() 
            ->success()
            ->title(__('Profile information updated'))
            ->body(__('Profile information has been updated successful'))
            ->send(); 
    }

    protected function getForms(): array
    {
        return [
            'updatePasswordForm',
            'updateProfileInformationForm'
        ];
    }

    protected function getUpdatePasswordFormAction(): Action
    {
        return Action::make('savePassword')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('savePassword');
    }

    protected function getUpdateProfileInformationFormAction(): Action
    {
        return Action::make('saveProfileInformation')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('saveProfileInformation');
    }

}
