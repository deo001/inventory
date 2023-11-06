<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
                ->success()
                ->title('Sale Created')
                ->body('Sale has been created successful!');
        
    }
}
