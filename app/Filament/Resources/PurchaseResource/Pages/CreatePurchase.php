<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Filament\Resources\PurchaseResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterCreate(): void 
    {        
        $this->record->product->quantity = $this->record->product->quantity + $this->record->quantity;
        $this->record->product->selling_price = $this->record->selling_price;
        $this->record->product->buying_price = $this->record->buying_price;
        $this->record->product->save();      
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Permission Creaated')
            ->body('Permission has been created successful!');
    }
}
