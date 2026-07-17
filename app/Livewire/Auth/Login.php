<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Models\LoginActivity;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        $throttleKey = Str::transliterate(Str::lower($this->email).'|'.request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('email', "Too many login attempts. Please try again in {$seconds} seconds.");
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($throttleKey);
            
            $user = Auth::user();
            
            // Log the login activity and device details
            LoginActivity::log($user->id, request()->ip(), request()->userAgent());

            // Regenerate session to prevent session fixation attacks
            session()->regenerate();

            session()->flash('success', 'Logged in successfully!');

            // Role-based redirection
            if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            if ($user->hasRole('Agent') || $user->role === 'agent') {
                return redirect()->intended(route('agent.dashboard'));
            }

            // Customer/Default
            return redirect()->intended(route('customer.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        $this->addError('email', 'Invalid email or password credentials.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }
}
