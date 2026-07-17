<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyInquiryResource\Pages;
use App\Models\PropertyInquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PropertyInquiryResource extends Resource
{
    protected static ?string $model = PropertyInquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'title')
                    ->disabled(),

                Forms\Components\TextInput::make('name')
                    ->disabled(),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->disabled(),

                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->disabled(),

                Forms\Components\Textarea::make('message')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.title')
                    ->label('Property')
                    ->searchable()
                    ->sortable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('name')
                    ->label('Contact Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertyInquiries::route('/'),
            'create' => Pages\CreatePropertyInquiry::route('/create'),
            'edit' => Pages\EditPropertyInquiry::route('/{record}/edit'),
        ];
    }
}
