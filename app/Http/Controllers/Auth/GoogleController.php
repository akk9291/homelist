<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Register a new customer user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'customer',
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $googleUser->getAvatar(),
                ]);
                $user->assignRole('Customer');
                $user->markEmailAsVerified(); // Since email is verified by Google
            } else {
                // Update Google ID and Avatar if they were not set
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar_url' => $googleUser->getAvatar(),
                    ]);
                }
            }

            Auth::login($user, true);

            // Log the login activity
            LoginActivity::log($user->id, request()->ip(), request()->userAgent());

            // Regenerate session
            session()->regenerate();

            session()->flash('success', 'Logged in successfully with Google!');

            // Role-based redirection
            if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
                return redirect()->intended('/admin');
            }

            if ($user->hasRole('Agent') || $user->role === 'agent') {
                return redirect()->intended(route('agent.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
}
