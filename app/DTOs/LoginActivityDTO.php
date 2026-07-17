<?php

namespace App\DTOs;

class LoginActivityDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly string $ipAddress,
        public readonly string $userAgent,
        public readonly string $deviceType,
        public readonly string $platform,
        public readonly string $browser
    ) {}

    /**
     * Create DTO from raw data.
     */
    public static function fromRequest(int $userId, string $ip, string $userAgent): self
    {
        $deviceType = 'desktop';
        $platform = 'Unknown';
        $browser = 'Unknown';

        // OS detection
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'OS X';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
            $deviceType = 'mobile';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
            $deviceType = 'mobile';
        }

        // Device type detection
        if (preg_match('/mobile|phone|ipod|blackberry|opera mini|iemobile/i', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            $deviceType = 'tablet';
        }

        // Browser detection
        if (preg_match('/msie|trident/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera/i', $userAgent)) {
            $browser = 'Opera';
        }

        return new self($userId, $ip, $userAgent, $deviceType, $platform, $browser);
    }
}
