<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $title = 'Site Settings';
    protected static ?string $navigationGroup = 'Settings';
    
    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::current();
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-computer-desktop')
                            ->schema([
                                TextInput::make('site_name')
                                    ->required()
                                    ->label('Site Name'),
                                TextInput::make('site_title')
                                    ->required()
                                    ->label('Site Title'),
                                Textarea::make('site_description')
                                    ->rows(3)
                                    ->label('Site Description'),
                                Textarea::make('footer_text')
                                    ->rows(3)
                                    ->label('Footer About Text'),
                                FileUpload::make('site_logo')
                                    ->image()
                                    ->directory('settings')
                                    ->label('Site Logo')
                                    ->disk('public'),
                                FileUpload::make('site_favicon')
                                    ->image()
                                    ->directory('settings')
                                    ->label('Site Favicon')
                                    ->disk('public'),
                            ]),
                        
                        Tabs\Tab::make('Contact Info')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('contact_email')
                                    ->email()
                                    ->required()
                                    ->label('Contact Email'),
                                TextInput::make('contact_phone')
                                    ->required()
                                    ->label('Contact Phone'),
                                TextInput::make('contact_whatsapp')
                                    ->label('WhatsApp Number'),
                                TextInput::make('contact_address')
                                    ->required()
                                    ->label('HQ Address (Indore)'),
                                TextInput::make('contact_address_nagpur')
                                    ->label('Branch Address (Nagpur)'),
                                Textarea::make('map_embed_code')
                                    ->rows(4)
                                    ->label('Google Map Embed Code'),
                            ]),
                        
                        Tabs\Tab::make('Social Links')
                            ->icon('heroicon-o-share')
                            ->schema([
                                TextInput::make('social_facebook')
                                    ->url()
                                    ->label('Facebook URL'),
                                TextInput::make('social_twitter')
                                    ->url()
                                    ->label('Twitter/X URL'),
                                TextInput::make('social_instagram')
                                    ->url()
                                    ->label('Instagram URL'),
                                TextInput::make('social_youtube')
                                    ->url()
                                    ->label('YouTube URL'),
                                TextInput::make('social_linkedin')
                                    ->url()
                                    ->label('LinkedIn URL'),
                            ]),

                        Tabs\Tab::make('Mail Settings')
                            ->icon('heroicon-o-envelope-open')
                            ->schema([
                                TextInput::make('mail_host')
                                    ->label('SMTP Host')
                                    ->placeholder('e.g. smtp.mailtrap.io'),
                                TextInput::make('mail_port')
                                    ->label('SMTP Port')
                                    ->placeholder('e.g. 2525'),
                                TextInput::make('mail_username')
                                    ->label('SMTP Username'),
                                TextInput::make('mail_password')
                                    ->password()
                                    ->revealable()
                                    ->label('SMTP Password'),
                                TextInput::make('mail_encryption')
                                    ->label('SMTP Encryption')
                                    ->placeholder('tls / ssl / null'),
                                TextInput::make('mail_from_address')
                                    ->email()
                                    ->label('Mail From Address')
                                    ->placeholder('e.g. info@homelist.co.in'),
                                TextInput::make('mail_from_name')
                                    ->label('Mail From Name')
                                    ->placeholder('e.g. Homelist Support'),
                            ]),
                    ])
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = Setting::current();
        $setting->update($this->form->getState());
        
        Setting::clearCache();

        Notification::make()
            ->title('Settings saved successfully!')
            ->success()
            ->send();
    }
}
