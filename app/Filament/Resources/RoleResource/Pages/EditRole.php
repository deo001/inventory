<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Filament\Actions;
use App\Filament\Resources\RoleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Role updated')
            ->body('Role has been updated successful');
    }
}
