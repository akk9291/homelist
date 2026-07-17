<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService extends BaseService
{
    /**
     * Send Email notification.
     */
    public function sendEmail(string $email, string $subject, string $body): bool
    {
        try {
            Mail::html($body, function ($message) use ($email, $subject) {
                $message->to($email)
                    ->subject($subject);
            });
            Log::info("Email notification sent to {$email}. Subject: {$subject}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS notification (Msg91/Twilio stub).
     */
    public function sendSms(string $phone, string $message): bool
    {
        Log::info("SMS Notification Sent to {$phone} -> Message: {$message}");
        
        // SMS Gateway API endpoint can be integrated here:
        // $apiUrl = "https://api.sms-gateway.com/send?to={$phone}&msg=" . urlencode($message);
        
        return true;
    }

    /**
     * Send WhatsApp notification (WhatsApp Cloud API stub).
     */
    public function sendWhatsApp(string $phone, string $message): bool
    {
        Log::info("WhatsApp Notification Sent to {$phone} -> Message: {$message}");
        
        // WhatsApp API endpoint:
        // $url = "https://graph.facebook.com/v17.0/" . config('services.whatsapp.phone_number_id') . "/messages";
        
        return true;
    }

    /**
     * Send In-App notification to User.
     */
    public function sendInApp(int $userId, string $title, string $message): bool
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        // Standard Laravel Database Notification channel or custom table
        // We will log it here.
        Log::info("In-App Notification created for User ID: {$userId} -> {$title}: {$message}");

        return true;
    }

    /**
     * Broadcast notification across all active channels.
     */
    public function broadcast(int $userId, string $title, string $message, array $channels = ['email', 'in_app']): void
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        if (in_array('email', $channels) && $user->email) {
            $this->sendEmail($user->email, $title, $message);
        }

        if (in_array('in_app', $channels)) {
            $this->sendInApp($userId, $title, $message);
        }

        if (in_array('sms', $channels) && method_exists($user, 'phone') && $user->phone) {
            $this->sendSms($user->phone, $message);
        }
    }
}
