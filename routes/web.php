<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\GoogleController;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\User\Dashboard;
use App\Livewire\Subscription\Checkout;
use App\Livewire\PropertyCompare;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{slug}/brochure', [PropertyController::class, 'brochure'])->name('properties.brochure');
Route::get('/properties/{slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/search', [PropertyController::class, 'search'])->name('properties.search');
Route::post('/inquiry', [PropertyController::class, 'inquiry'])->name('properties.inquiry');

Route::get('/agents/{id}', [AgentController::class, 'show'])->name('agents.show');

Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/blog', [PageController::class, 'blog'])->name('pages.blog');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

    // Google Socialite Routes
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', \App\Livewire\Auth\VerifyEmail::class)
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Dispatcher redirecting according to role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
            return redirect('/admin');
        }
        if ($user->hasRole('Agent') || $user->role === 'agent') {
            return redirect()->route('agent.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    Route::get('/checkout', Checkout::class)->name('subscription.checkout');
    
    Route::get('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    })->name('logout');
});

// Customer Dashboard Routes
Route::middleware(['auth', 'verified', 'customer'])->prefix('account')->group(function () {
    Route::get('/dashboard', \App\Livewire\Customer\CustomerDashboard::class)->name('customer.dashboard');
});

// Agent Dashboard Routes
Route::middleware(['auth', 'verified', 'agent'])->prefix('agent')->group(function () {
    Route::get('/dashboard', \App\Livewire\Agent\AgentDashboard::class)->name('agent.dashboard');
});

// Compare Properties
Route::get('/compare', PropertyCompare::class)->name('compare');

Route::get('/compare/add/{id}', function ($id) {
    $compareList = session()->get('compare_list', []);
    if (!in_array($id, $compareList)) {
        if (count($compareList) >= 3) {
            return redirect()->back()->with('error', 'You can only compare up to 3 properties.');
        }
        $compareList[] = $id;
        session()->put('compare_list', $compareList);
    }
    return redirect()->route('compare')->with('success', 'Added to compare list.');
})->name('compare.add');

Route::get('/wishlist/toggle/{id}', function ($id) {
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Please log in to add properties to your wishlist.');
    }
    auth()->user()->favoriteProperties()->toggle($id);
    return redirect()->back()->with('success', 'Wishlist updated.');
})->name('wishlist.toggle');
