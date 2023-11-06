<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Filament\Resources\SaleResource;
use App\Filament\Resources\SaleResource\Pages\CreateSale;
use App\Filament\Resources\SaleResource\Pages\EditSale;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Mail\Mailables\Content;

use function PHPUnit\Framework\isNan;

class SalesRelationManager extends RelationManager
{
    protected static string $relationship = 'sales';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('selling_price')
                                    ->default($this->ownerRecord->selling_price)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('quantity')
                                    ->default(0)
                                    ->required()
                                    ->live()
                                    ->numeric(),    
                            ])
                    ]),      
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('totalPrice')
                                    ->content(function (Get $get) {
                                        if($get('quantity') != ''){
                                            if(!is_nan($get('quantity')) && $get('quantity') > 0){
                                                return number_format($get('selling_price') * $get('quantity'), 2);
                                            }
                                            return 0;
                                        }
                                        return 0;
                                    })  
                            ])
                    ])        
            ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product name'),
                Tables\Columns\TextColumn::make('selling_price')
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
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire): array {
                        $data['buying_price'] = $livewire->ownerRecord->buying_price;
                
                        return $data;
                    })
                    ->after(function (array $data, RelationManager $livewire): void {
                        if($data['quantity'] > 0){
                            $livewire->ownerRecord->quantity = $livewire->ownerRecord->quantity - $data['quantity'];
                            $livewire->ownerRecord->save();
                            $livewire->ownerRecord->refresh();
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Sale Created')
                            ->body('Sale has been created successful!'),
                    )
                    ->successRedirectUrl(fn (RelationManager $livewire): string => "/products/{$livewire->ownerRecord->id}"),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Sale Updated')
                        ->body('Sale has been updated successful!'),
                ),
                Tables\Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Sale Deleted')
                        ->body('Sale has been deleted successful!'),
                ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
