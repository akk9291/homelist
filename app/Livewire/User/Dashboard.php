<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Agent;
use App\Models\Payment;
use App\Models\PropertyInquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Dashboard extends Component
{
    use WithFileUploads;

    public string $activeTab = 'profile'; // profile, kyc, wishlist, bookings, payments

    // Profile fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    // KYC fields
    public $kycFile;
    public ?string $phone = '';
    public ?string $about = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->name = $user->name;
        $this->email = $user->email;

        if ($user->agent) {
            $this->phone = $user->agent->phone;
            $this->about = $user->agent->about;
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        session()->flash('success', 'Profile updated successfully!');
    }

    public function uploadKyc()
    {
        $user = Auth::user();
        if (!$user->agent) {
            return;
        }

        $this->validate([
            'kycFile' => 'required|file|mimes:jpeg,png,pdf|max:5120', // Max 5MB
            'phone' => 'required|string|max:20',
            'about' => 'nullable|string',
        ]);

        $filePath = $this->kycFile->store('kyc', 'public');

        $user->agent->update([
            'phone' => $this->phone,
            'about' => $this->about,
            'kyc_status' => 'pending',
            'kyc_document_path' => $filePath,
        ]);

        session()->flash('success', 'KYC documents uploaded successfully! Admin will review shortly.');
    }

    public function toggleWishlist(int $propertyId)
    {
        $user = Auth::user();
        $user->favoriteProperties()->detach($propertyId);
        session()->flash('success', 'Removed from wishlist.');
    }

    public function render()
    {
        $user = Auth::user();
        
        $wishlist = $user->favoriteProperties()->with(['type', 'status', 'city', 'state'])->get();
        $inquiries = PropertyInquiry::where('email', $user->email)
            ->orWhere('phone', $user->agent?->phone ?? 'N/A')
            ->with('property')
            ->latest()
            ->get();
            
        $payments = Payment::where('user_id', $user->id)
            ->with('subscriptionPlan')
            ->latest()
            ->get();

        return view('livewire.user.dashboard', [
            'wishlist' => $wishlist,
            'inquiries' => $inquiries,
            'payments' => $payments,
            'agent' => $user->agent,
        ])->layout('layouts.app');
    }
}
