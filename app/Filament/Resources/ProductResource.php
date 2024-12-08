<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = "Products Store"; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->required()
             
                ->label('Name'),
                TextInput::make('price')
                ->required()
                ->numeric()
                ->label('Price'),
                FileUpload::make('images')
                ->image()
                ->multiple() 
                ->required()
                ->maxSize(2150)
                ->directory('products')  
                ->label('Product Image'),
             

            Textarea::make('description')
                ->required()
                ->label('Description'),

            Select::make('status')
                ->options([
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out of Stock',
                    'discontinued' => 'Discontinued',
                ])
                ->default('in_stock')
                ->label('Status'),

            Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->label('Category'),

            TextInput::make('rating')
                ->numeric()
                ->default(0)
                ->label('Rating'),

            TextInput::make('sold')
                ->numeric()
                ->default(0)
                ->label('Sold'),

            DatePicker::make('published_at')
                ->nullable()
                ->label('Published At'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),
              
                  ImageColumn::make('images') 
                  ->label('Images')
        
                  ->sortable()
                  ->searchable()
                  ->getStateUsing(fn($record) => $record->images ? $record->images[0] : null), 
          
                TextColumn::make('description')
                ->label('Description')
                ->sortable(),
                TextColumn::make('price')->label('Price') ->sortable()->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 2)),
                BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'success' => 'In Stock',     
                    'warning' => 'Out Of Stock', 
                    'red' => 'Discontinued',    
                ])
                ->sortable(),
                TextColumn::make('category.name')->label('Category') ->sortable(),
                TextColumn::make('sold')->label('Sold') ->sortable(),
                TextColumn::make('rating')->label('Rating') ->sortable(),
                TextColumn::make('published_at')->label('Published At') ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                ->options([
                    'in_stock' => 'In Stock',
                    'out_of_stock' => 'Out of Stock',
                    'discontinued' => 'Discontinued',
                ])
                ->label('Status'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
