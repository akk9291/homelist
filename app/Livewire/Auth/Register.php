<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\AgentWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'customer'; // customer, agent

    protected array $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:customer,agent',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        if ($this->role === 'agent') {
            $user->assignRole('Agent');
            // Create Agent Profile
            $user->agent()->create([
                'phone' => '',
                'photo' => null,
                'about' => '',
                'kyc_status' => 'unverified',
                'is_verified' => false,
            ]);
            // Create Agent Wallet
            AgentWallet::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'commission_rate' => 2.50,
            ]);
        } else {
            $user->assignRole('Customer');
        }

        // Trigger Laravel standard registration event which sends the verification email
        event(new Registered($user));

        Auth::login($user);

        session()->flash('success', 'Account registered successfully! A verification link has been sent to your email.');

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.app');
    }
}
