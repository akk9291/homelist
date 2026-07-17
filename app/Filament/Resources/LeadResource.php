<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\CrmLead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = CrmLead::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->email(),

                Forms\Components\TextInput::make('phone'),

                Forms\Components\Select::make('source')
                    ->options([
                        'website' => 'Website',
                        'manual' => 'Manual Entry',
                        'whatsapp' => 'WhatsApp Inquiry',
                        'portal' => 'Property Portal',
                    ])
                    ->default('website')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New Lead',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'proposal' => 'Proposal Sent',
                        'won' => 'Won (Closed)',
                        'lost' => 'Lost',
                    ])
                    ->default('new')
                    ->required(),

                Forms\Components\Select::make('agent_id')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'title')
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('source')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'contacted' => 'warning',
                        'qualified' => 'primary',
                        'proposal' => 'secondary',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.name')->label('Agent')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
