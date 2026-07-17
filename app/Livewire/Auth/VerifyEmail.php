<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function resend()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $user->sendEmailVerificationNotification();

        session()->flash('success', 'A new verification link has been sent to the email address you provided during registration.');
    }

    public function render()
    {
        return view('livewire.auth.verify-email')->layout('layouts.app');
    }
}
