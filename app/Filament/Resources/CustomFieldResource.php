<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomFieldResource\Pages;
use App\Models\CustomField;
use App\Models\CustomFieldGroup;
use App\Models\PropertyType;
use App\Models\City;
use App\Models\State;
use App\Models\SubscriptionPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CustomFieldResource extends Resource
{
    protected static ?string $model = CustomField::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 12;
    protected static ?string $navigationLabel = 'Custom Fields Builder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Configuration')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('group_id')
                            ->label('Group')
                            ->options(CustomFieldGroup::pluck('name', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('field_type')
                            ->label('Field Type')
                            ->options([
                                'text' => 'Text',
                                'number' => 'Number',
                                'decimal' => 'Decimal',
                                'textarea' => 'Textarea',
                                'richtext' => 'Rich Text Editor',
                                'email' => 'Email',
                                'phone' => 'Mobile Number',
                                'url' => 'URL',
                                'date' => 'Date',
                                'datetime' => 'Date & Time',
                                'time' => 'Time',
                                'color' => 'Color Picker',
                                'select' => 'Dropdown (Single)',
                                'multiselect' => 'Multi Select',
                                'radio' => 'Radio Button',
                                'checkbox' => 'Checkbox',
                                'toggle' => 'Toggle Switch',
                                'file' => 'File Upload',
                                'multiple_file' => 'Multiple File Upload',
                                'image' => 'Image Upload',
                                'multiple_image' => 'Multiple Image Upload',
                                'map_picker' => 'Google Map Picker',
                                'currency' => 'Currency',
                                'area_unit' => 'Area Unit',
                                'password' => 'Password (future use)',
                                'json' => 'JSON Field',
                            ])
                            ->required()
                            ->live(),
                    ]),

                Forms\Components\Section::make('Validation & Layout')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('placeholder')
                            ->placeholder('e.g., Enter modular kitchen specifications'),

                        Forms\Components\TextInput::make('default_value')
                            ->placeholder('e.g., Yes'),

                        Forms\Components\TextInput::make('validation_rules')
                            ->label('Custom Validation Rules')
                            ->placeholder('e.g., min:3|max:100|regex:/^[A-Z]+/i')
                            ->helperText('Use standard Laravel validation strings separated by pipelines.'),

                        Forms\Components\Select::make('width')
                            ->options([
                                25 => '25% Width',
                                50 => '50% Width',
                                75 => '75% Width',
                                100 => '100% Width',
                            ])
                            ->default(100)
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Options (Choices)')
                    ->description('Specify choices for Dropdowns, Multi-Selects, Radio Buttons, or Checkboxes.')
                    ->hidden(fn (Forms\Get $get) => !in_array($get('field_type'), ['select', 'multiselect', 'radio', 'checkbox']))
                    ->schema([
                        Forms\Components\Repeater::make('options_json')
                            ->label('Options List')
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->required()
                                    ->placeholder('e.g., marble'),
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->placeholder('e.g., Premium Marble Flooring'),
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Conditional Visibility Logic')
                    ->description('Set conditional rules for when this field should be rendered in the form.')
                    ->schema([
                        Forms\Components\Repeater::make('conditional_logic')
                            ->label('Conditions')
                            ->schema([
                                Forms\Components\TextInput::make('depend_on_field_slug')
                                    ->label('Dependent On Field Slug')
                                    ->required()
                                    ->placeholder('e.g., property_status_id or any custom field slug'),
                                Forms\Components\Select::make('operator')
                                    ->options([
                                        'equals' => 'Equals',
                                        'not_equals' => 'Does Not Equal',
                                        'contains' => 'Contains',
                                        'not_contains' => 'Does Not Contain',
                                        'empty' => 'Is Empty',
                                        'not_empty' => 'Is Not Empty',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->placeholder('e.g., residential or true'),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Feature Flags')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('required')->default(false),
                        Forms\Components\Toggle::make('searchable')->default(false),
                        Forms\Components\Toggle::make('sortable')->default(false),
                        Forms\Components\Toggle::make('filterable')->default(false),
                        Forms\Components\Toggle::make('exportable')->default(true),
                        Forms\Components\Toggle::make('is_repeatable')->default(false),
                    ]),

                Forms\Components\Section::make('Taxonomy Assignment Rules')
                    ->description('Map this custom field to render only on selected property types, categories, or cities. Leaving selections empty applies them globally.')
                    ->schema([
                        Forms\Components\Repeater::make('assignments')
                            ->relationship('assignments')
                            ->schema([
                                Forms\Components\Select::make('property_type_id')
                                    ->label('Property Type')
                                    ->options(PropertyType::pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),
                                Forms\Components\Select::make('city_id')
                                    ->label('City')
                                    ->options(City::pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),
                                Forms\Components\Select::make('state_id')
                                    ->label('State')
                                    ->options(State::pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),
                                Forms\Components\Select::make('subscription_plan_id')
                                    ->label('Subscription Plan')
                                    ->options(SubscriptionPlan::pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('slug')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('group.name')->label('Group')->sortable(),
                Tables\Columns\TextColumn::make('field_type')->label('Type')->sortable(),
                Tables\Columns\IconColumn::make('required')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group_id')
                    ->label('Group')
                    ->options(CustomFieldGroup::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('field_type')
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'select' => 'Dropdown',
                        'checkbox' => 'Checkbox',
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
            'index' => Pages\ListCustomFields::route('/'),
            'create' => Pages\CreateCustomField::route('/create'),
            'edit' => Pages\EditCustomField::route('/{record}/edit'),
        ];
    }
}
