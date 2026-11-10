<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_title',
        'site_description',
        'site_logo',
        'site_favicon',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'contact_address',
        'contact_address_nagpur',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_youtube',
        'social_linkedin',
        'map_embed_code',
        'footer_text',
        'website_views',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    public static function current()
    {
        return Cache::rememberForever('site_settings', function () {
            return self::first() ?: self::createDefault();
        });
    }

    public static function clearCache()
    {
        Cache::forget('site_settings');
    }

    protected static function booted()
    {
        static::saved(function ($setting) {
            self::clearCache();
        });
    }

    protected static function createDefault()
    {
        return self::create([
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
            'mail_host' => 'mailpit',
            'mail_port' => '1025',
            'mail_encryption' => 'null',
        ]);
    }
}
