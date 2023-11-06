<x-filament-panels::page>
    <x-filament::section 
        :heading="'Update personal information'"
        :description="'Update your account\'s profile information and email address'"
    >
        <x-filament-panels::form wire:submit="saveProfileInformation">        
            {{$this->updateProfileInformationForm}}
            <x-filament-panels::form.actions :actions="[$this->getUpdateProfileInformationFormAction()]" />
        </x-filament-panels::form>
    </x-filament::section>
    <x-filament::section 
        :heading="'Update password Form'"
        :description="'Ensure your account is using a long, random password to stay secure'"
    >
        <x-filament-panels::form wire:submit="savePassword">        
            {{$this->updatePasswordForm}}
            <x-filament-panels::form.actions :actions="[$this->getUpdatePasswordFormAction()]" /> 
        </x-filament-panels::form>
    </x-filament::section>
</x-filament-panels::page>
