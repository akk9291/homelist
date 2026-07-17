<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\City;
use App\Models\Property;
use App\Models\State;
use App\Models\User;
use App\Models\PropertyType;
use App\Models\PropertyStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Property Details')
                    ->tabs([
                        // Tab 1: Basic Info
                        Forms\Components\Tabs\Tab::make('Basic Info')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->numeric()
                                            ->prefix('₹')
                                            ->required(),

                                        Forms\Components\Select::make('property_type_id')
                                            ->label('Property Type')
                                            ->options(PropertyType::pluck('name', 'id'))
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Select::make('property_status_id')
                                            ->label('Property Status')
                                            ->options(PropertyStatus::pluck('name', 'id'))
                                            ->searchable()
                                            ->required(),
                                    ]),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('agent_id')
                                            ->label('Agent')
                                            ->options(User::where('role', 'agent')->pluck('name', 'id'))
                                            ->searchable(),

                                        Forms\Components\Toggle::make('featured')
                                            ->label('Featured Property')
                                            ->inline(false),

                                        Forms\Components\Toggle::make('published')
                                            ->label('Published (Visible to public)')
                                            ->default(true)
                                            ->inline(false),
                                    ]),
                            ]),

                        // Tab 2: Location
                        Forms\Components\Tabs\Tab::make('Location')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('state_id')
                                            ->label('State')
                                            ->options(State::pluck('name', 'id'))
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('city_id', null))
                                            ->required(),

                                        Forms\Components\Select::make('city_id')
                                            ->label('City')
                                            ->options(fn (Get $get) => City::where('state_id', $get('state_id'))->pluck('name', 'id'))
                                            ->required(),
                                    ]),

                                Forms\Components\TextInput::make('address')
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->numeric()
                                            ->helperText('Google Maps Latitude'),

                                        Forms\Components\TextInput::make('longitude')
                                            ->numeric()
                                            ->helperText('Google Maps Longitude'),
                                    ]),
                            ]),

                        // Tab 3: Amenities & Specifications
                        Forms\Components\Tabs\Tab::make('Amenities & Details')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('bedrooms')
                                            ->numeric()
                                            ->integer(),

                                        Forms\Components\TextInput::make('bathrooms')
                                            ->numeric()
                                            ->integer(),

                                        Forms\Components\TextInput::make('area')
                                            ->label('Area (Sq. Ft.)')
                                            ->numeric(),
                                    ]),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('facing')
                                            ->options([
                                                'East' => 'East',
                                                'West' => 'West',
                                                'North' => 'North',
                                                'South' => 'South',
                                                'North-East' => 'North-East',
                                                'North-West' => 'North-West',
                                                'South-East' => 'South-East',
                                                'South-West' => 'South-West',
                                            ]),

                                        Forms\Components\Select::make('ownership')
                                            ->options([
                                                'Freehold' => 'Freehold',
                                                'Leasehold' => 'Leasehold',
                                                'Co-operative Society' => 'Co-operative Society',
                                                'Power of Attorney' => 'Power of Attorney',
                                            ]),

                                        Forms\Components\Select::make('furnishing')
                                            ->options([
                                                'Furnished' => 'Furnished',
                                                'Unfurnished' => 'Unfurnished',
                                                'Semi-Furnished' => 'Semi-Furnished',
                                            ]),
                                    ]),

                                Forms\Components\CheckboxList::make('amenities')
                                    ->relationship('amenities', 'name')
                                    ->columns(3)
                                    ->gridDirection('column')
                                    ->columnSpanFull(),
                            ]),

                        // Tab 4: Media & Floor Plans
                        Forms\Components\Tabs\Tab::make('Media & Floor Plans')
                            ->schema([
                                Forms\Components\Section::make('Property Gallery')
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('featured_image')
                                            ->collection('featured')
                                            ->label('Featured Image')
                                            ->responsiveImages(),

                                        SpatieMediaLibraryFileUpload::make('gallery')
                                            ->collection('gallery')
                                            ->multiple()
                                            ->reorderable()
                                            ->label('Gallery Images')
                                            ->responsiveImages(),
                                    ]),

                                Forms\Components\Section::make('Floor Plans')
                                    ->schema([
                                        Forms\Components\Repeater::make('floorPlans')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required(),
                                                Forms\Components\TextInput::make('price')
                                                    ->numeric()
                                                    ->prefix('₹'),
                                                Forms\Components\TextInput::make('size')
                                                    ->numeric()
                                                    ->label('Size (Sq. Ft.)'),
                                                Forms\Components\TextInput::make('bedrooms')
                                                    ->numeric()
                                                    ->integer(),
                                                Forms\Components\TextInput::make('bathrooms')
                                                    ->numeric()
                                                    ->integer(),
                                                Forms\Components\FileUpload::make('image')
                                                    ->image()
                                                    ->directory('floor_plans'),
                                                Forms\Components\Textarea::make('description')
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull()
                                            ->defaultItems(0),
                                    ]),
                            ]),

                        // Tab 5: SEO
                        Forms\Components\Tabs\Tab::make('SEO Settings')
                            ->schema([
                                Forms\Components\Section::make('SEO Metadata')
                                    ->relationship('seo')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->maxLength(60),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->maxLength(160),
                                        Forms\Components\TextInput::make('keywords')
                                            ->helperText('Comma-separated words'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wp_property_id')
                    ->label('WP ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')
                    ->collection('featured')
                    ->label('Photo')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('type.name')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status.name')
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('city.name')
                    ->label('City')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('featured')
                    ->label('Featured')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('published')
                    ->label('Published')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                
                Tables\Filters\SelectFilter::make('property_type_id')
                    ->label('Property Type')
                    ->relationship('type', 'name'),

                Tables\Filters\SelectFilter::make('property_status_id')
                    ->label('Property Status')
                    ->relationship('status', 'name'),

                Tables\Filters\SelectFilter::make('state_id')
                    ->label('State')
                    ->relationship('state', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
