<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = SubscriptionPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Subscription Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('INR'),

                Forms\Components\Select::make('billing_period')
                    ->options([
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'yearly' => 'Yearly',
                        'lifetime' => 'Lifetime',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('property_limit')
                    ->label('Property Posting Limit')
                    ->numeric()
                    ->placeholder('Unlimited'),

                Forms\Components\TextInput::make('featured_limit')
                    ->label('Featured Property Limit')
                    ->numeric()
                    ->placeholder('Unlimited'),

                Forms\Components\TextInput::make('image_limit')
                    ->label('Image Upload Limit')
                    ->numeric()
                    ->placeholder('Unlimited'),

                Forms\Components\TextInput::make('video_limit')
                    ->label('Video Upload Limit')
                    ->numeric()
                    ->placeholder('Unlimited'),

                Forms\Components\TextInput::make('trial_days')
                    ->label('Trial Days')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('price')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('billing_period')->sortable(),
                Tables\Columns\TextColumn::make('property_limit')->label('Prop Limit')->sortable(),
                Tables\Columns\TextColumn::make('featured_limit')->label('Feat Limit')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit' => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
