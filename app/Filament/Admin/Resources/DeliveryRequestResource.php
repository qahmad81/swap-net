<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DeliveryRequestResource\Pages;
use App\Filament\Admin\Resources\DeliveryRequestResource\RelationManagers;
use App\Models\DeliveryRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryRequestResource extends Resource
{
    protected static ?string $model = DeliveryRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('listing_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('offer_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('requester_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('offerer_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('delivery_cost')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cost_bearer')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('listing_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('offer_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('requester_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('offerer_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_bearer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
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
            'index' => Pages\ListDeliveryRequests::route('/'),
            'create' => Pages\CreateDeliveryRequest::route('/create'),
            'view' => Pages\ViewDeliveryRequest::route('/{record}'),
            'edit' => Pages\EditDeliveryRequest::route('/{record}/edit'),
        ];
    }
}
