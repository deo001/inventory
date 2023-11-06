<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PurchasesRelationManager extends RelationManager
{
    protected static string $relationship = 'purchases';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('buying_price')
                    ->default($this->ownerRecord->buying_price)
                    ->required()
                    ->numeric(),  
                Forms\Components\TextInput::make('selling_price')
                    ->default($this->ownerRecord->selling_price)
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(), 
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product name'),
                Tables\Columns\TextColumn::make('buying_price')
                    ->label('Price'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Created by')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (array $data, RelationManager $livewire): void {
                        if($data['quantity'] > 0){
                            $livewire->ownerRecord->quantity = $livewire->ownerRecord->quantity + $data['quantity'];
                            $livewire->ownerRecord->selling_price = $data['selling_price'];
                            $livewire->ownerRecord->buying_price = $data['buying_price'];
                            $livewire->ownerRecord->save();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Purchase Created')
                            ->body('Purchase has been created successful!'),
                    )
                    ->successRedirectUrl(fn (RelationManager $livewire): string => "/products/{$livewire->ownerRecord->id}?activeRelationManager=1"),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Purchase Updated')
                            ->body('Purchase has been updated successful!'),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Purchase Deleted')
                            ->body('Purchase has been deleted successful!'),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
