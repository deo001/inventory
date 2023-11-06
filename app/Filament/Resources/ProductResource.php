<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Infolists\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->createOptionForm(fn (Form $form) => CategoryResource::form($form)->getComponents()),            
                Forms\Components\TextInput::make('buying_price')
                    ->numeric(),
                Forms\Components\TextInput::make('selling_price')
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_visible'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Available')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Created by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('is_visible')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Visible' : 'Not visible')
                    ->color(fn (bool $state): string => match($state) {
                        true => 'success',
                        false => 'warning'
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Product Deleted')
                            ->body('Product has been deleted successful!'),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => auth()->user()->hasRole('super admin') ? $query : $query->where('is_visible', true));
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([        
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Grid::make(3)
                                ->schema([
                                    Infolists\Components\ImageEntry::make('image')
                                        ->hiddenLabel()
                                        ->grow(false)
                                        ->visible(fn (Product $record): bool => $record->image ? true : false),
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('name')
                                            ->label('Product Name'),
                                        Infolists\Components\TextEntry::make('category.name'),
                                        Infolists\Components\TextEntry::make('selling_price'),
                                    ]),
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('user.full_name')
                                            ->label('Created by'),
                                        Infolists\Components\TextEntry::make('quantity')
                                            ->label('Available in stock')
                                            ->badge()
                                            ->color('success'),
                                    ]),
                                ]),

                        ])->from('lg'),
                    ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\SalesRelationManager::class,
            RelationManagers\PurchasesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}
