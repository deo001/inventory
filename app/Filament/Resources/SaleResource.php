<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\SalesRelationManager;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->afterStateUpdated(function ($state, Set $set): void 
                                    {
                                        if($state != ''){
                                            $product = Product::findOrFail($state);
                                            $set('selling_price', $product->selling_price);
                                            $set('buying_price', $product->buying_price);
                                        }
                                        else {
                                            $set('selling_price', null);
                                            $set('buying_price', null);
                                        }
                                    }),
                                Forms\Components\TextInput::make('selling_price')
                                    ->label('Price')
                                    ->suffix('Tsh')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->live()
                                    ->numeric(),
                                Forms\Components\Hidden::make('buying_price')
                                    ->required(),
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
                                                return number_format($get('selling_price') * $get('quantity'), 2) . ' Tsh';
                                            }
                                            return 0;
                                        }
                                        return 0;
                                    }) 
                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Price')
                    ->numeric(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Created by')
                    ->toggleable(isToggledHiddenByDefault: true),                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Purchase Deleted')
                            ->body('Purchase has been deleted successful')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->latest());
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }    
}
