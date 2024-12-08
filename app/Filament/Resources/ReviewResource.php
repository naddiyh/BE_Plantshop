<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $navigationGroup = "Feedback Customer"; 
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                ->label('Product')
                ->options(\App\Models\Product::all()->pluck('name', 'id'))
                ->required(),
            
            Select::make('user_id')
                ->label('User')
                ->options(\App\Models\User::all()->pluck('name', 'id'))
                ->required(),
            
            Textarea::make('review')
                ->label('Review')
                ->required()
                ->maxLength(500),
            
            Select::make('rating')
                ->label('Rating')
                ->options([1, 2, 3, 4, 5])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('product.name')
                ->label('Product')
                ->sortable(),
            
            TextColumn::make('user.name')
                ->label('User')
                ->sortable(),

            TextColumn::make('review')
                ->label('Review')
                ->limit(50), 
            
            
                BadgeColumn::make('rating')
                ->label('Rating')
                ->formatStateUsing(fn ($state) => match ($state) {
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                    4 => 'Four',
                    5 => 'Five',
                    default => 'Unknown',
                }),
            TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->sortable(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
