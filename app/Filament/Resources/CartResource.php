<?php

namespace App\Filament\Resources;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\Cart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Hidden;
class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = "Products Store"; 
    public static function form(Form $form): Form
    {
        return $form
        ->schema([

            Select::make('product_id')
                ->label('Product')
                ->options(\App\Models\Product::all()->pluck('name', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $get, $set) {
                    if ($state) {
                        $product = \App\Models\Product::find($state);
                        if ($product) {
                            $set('price', $product->price); // Set price dynamically when product is selected
                            $set('total', $get('quantity') * $product->price); // Recalculate total dynamically
                        }
                    }
                }),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->default(1)
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $get, $set) {
                    $price = $get('price');
                    if ($price && $state) {
                        $set('total', $state * $price);
                    }
                }),

            TextInput::make('price')
                ->label('Price')
                ->disabled()  
                ->default(fn($get) => $get('product_id') ? \App\Models\Product::find($get('product_id'))->price : null),

         
            TextInput::make('total')
                ->label('Total')
                ->disabled() 
                ->default(function ($get) {
                    $quantity = $get('quantity');
                    $price = $get('price');
                    return ($quantity && $price) ? $quantity * $price : null;
                }),

            Hidden::make('user_id')
                ->default(fn() => Auth::id())
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')
                ->searchable(), 
                TextColumn::make('product.name')->label('Product'),
                TextColumn::make('quantity')->label('Quantity'),
                TextColumn::make('price')->label('Price')->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 2)),
                TextColumn::make('total') // This column will now calculate total dynamically
                ->label('Total')
                ->formatStateUsing(function ($state, $record) {
                    return 'Rp ' . number_format($record->quantity * $record->price, 2);
                }),
          
                ])
        
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }
}
