<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\PropertyRepositoryInterface::class,
            \App\Repositories\Eloquent\PropertyRepository::class
        );
        $this->app->bind(
            \App\Repositories\LeadRepositoryInterface::class,
            \App\Repositories\Eloquent\LeadRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\LoginActivityRepositoryInterface::class,
            \App\Repositories\Eloquent\LoginActivityRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\CustomFieldRepositoryInterface::class,
            \App\Repositories\Eloquent\CustomFieldRepository::class
        );
    }

    public function boot(): void
    {
        // Share settings globally
        $settingsObj = null;
        if (!app()->runningInConsole() || app()->runningUnitTests()) {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settingsObj = \App\Models\Setting::current();
                \Illuminate\Support\Facades\View::share('settings', $settingsObj);
            }
        } else {
            // Provide a dummy settings object in CLI if needed, to avoid breaking commands that boot the application
            $settingsObj = new \App\Models\Setting([
                'site_name' => 'Homelist',
                'site_title' => 'Homelist Real Estate Portal',
                'site_description' => 'Find your dream house, commercial properties, and lands with Homelist.',
                'contact_email' => 'info@homelist.co.in',
                'contact_phone' => '+91 98265 23333',
                'contact_whatsapp' => '+91 98265 23333',
                'contact_address' => '120, Silver Business Park, A.B. Road, Indore, MP, India',
                'contact_address_nagpur' => '45, Hingna Road, Mahajan Wadi, Nagpur, MH, India',
                'social_facebook' => 'https://facebook.com',
                'social_twitter' => 'https://twitter.com',
                'social_instagram' => 'https://instagram.com',
                'social_youtube' => 'https://youtube.com',
                'social_linkedin' => 'https://linkedin.com',
                'footer_text' => 'The leading real estate marketplace to buy, rent, or lease premium properties. Serving Madhya Pradesh, Maharashtra, and beyond.',
            ]);
            \Illuminate\Support\Facades\View::share('settings', $settingsObj);
        }

        // Apply SMTP Mail Configuration from database dynamically
        if ($settingsObj && $settingsObj->mail_host) {
            config([
                'mail.mailers.smtp.host' => $settingsObj->mail_host,
                'mail.mailers.smtp.port' => $settingsObj->mail_port,
                'mail.mailers.smtp.username' => $settingsObj->mail_username,
                'mail.mailers.smtp.password' => $settingsObj->mail_password,
                'mail.mailers.smtp.encryption' => $settingsObj->mail_encryption,
                'mail.from.address' => $settingsObj->mail_from_address ?: $settingsObj->contact_email,
                'mail.from.name' => $settingsObj->mail_from_name ?: $settingsObj->site_name,
            ]);
        }
    }
}
