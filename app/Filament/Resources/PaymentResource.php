<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\UserSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-rupee';
    protected static ?string $navigationGroup = 'Subscription Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('subscription_plan_id')
                    ->relationship('subscriptionPlan', 'name')
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('gst_amount')
                    ->numeric()
                    ->default(0.00),

                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('payment_gateway')
                    ->options([
                        'razorpay' => 'Razorpay',
                        'phonepe' => 'PhonePe',
                        'bank_transfer' => 'Bank Transfer',
                        'offline' => 'Offline Cash',
                    ])
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\TextInput::make('transaction_id')
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('invoice_number')
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('utr_number')
                    ->label('UTR / Reference Number')
                    ->unique(ignoreRecord: true),

                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('subscriptionPlan.name')->label('Plan')->sortable(),
                Tables\Columns\TextColumn::make('total_amount')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('payment_gateway')->label('Gateway')->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Custom Action to Approve Bank/Offline Transfer
                Tables\Actions\Action::make('approve')
                    ->label('Approve UTR')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Payment $record) => $record->payment_status === 'pending')
                    ->action(function (Payment $record) {
                        $record->update([
                            'payment_status' => 'completed',
                            'manual_approved_by' => auth()->id(),
                            'manual_approved_at' => now(),
                        ]);

                        // Grant Subscription automatically
                        UserSubscription::create([
                            'user_id' => $record->user_id,
                            'subscription_plan_id' => $record->subscription_plan_id,
                            'starts_at' => now(),
                            'ends_at' => $record->subscriptionPlan->billing_period === 'lifetime'
                                ? null
                                : ($record->subscriptionPlan->billing_period === 'yearly'
                                    ? now()->addYear()
                                    : ($record->subscriptionPlan->billing_period === 'quarterly'
                                        ? now()->addMonths(3)
                                        : now()->addMonth())),
                            'status' => 'active',
                        ]);
                    }),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
