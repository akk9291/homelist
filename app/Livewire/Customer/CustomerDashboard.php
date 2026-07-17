<?php

namespace App\Livewire\Customer;

use App\Models\User;
use App\Models\Property;
use App\Models\PropertyBooking;
use App\Models\SavedSearch;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class CustomerDashboard extends Component
{
    use WithFileUploads;

    public string $activeTab = 'overview'; // overview, profile, wishlist, compare, saved_searches, bookings, reviews, tickets, subscriptions, calculators, settings

    // Profile Details
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $photo;
    public ?string $avatarUrl = '';

    // Support Tickets
    public string $ticketSubject = '';
    public string $ticketDescription = '';
    public string $ticketCategory = 'general';
    public string $ticketPriority = 'medium';
    public ?SupportTicket $selectedTicket = null;
    public string $replyMessage = '';

    // Loan/EMI Calculator
    public float $loanAmount = 1000000;
    public float $interestRate = 8.5;
    public int $loanTerm = 20; // years
    public float $emi = 0;
    public float $totalPayment = 0;
    public float $totalInterest = 0;

    // Notification/Privacy Settings
    public bool $emailNotifications = true;
    public bool $smsNotifications = false;
    public bool $profilePublic = false;

    // Compare session list
    public array $compareList = [];

    protected $queryString = ['activeTab'];

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatarUrl = $user->avatar_url;

        // Populate compare list from session
        $this->compareList = session()->get('compare_list', []);

        // Calculate initial EMI
        $this->calculateEmi();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['loanAmount', 'interestRate', 'loanTerm'])) {
            $this->calculateEmi();
        }
    }

    // --- Profile Management ---
    public function updateProfile()
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($this->photo) {
            $rules['photo'] = 'image|max:1024'; // 1MB Max
        }

        if (!empty($this->password)) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;

        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }

        if ($this->photo) {
            $path = $this->photo->store('avatars', 'public');
            $user->avatar_url = '/storage/' . $path;
            $this->avatarUrl = $user->avatar_url;
        }

        $user->save();

        session()->flash('success', 'Profile updated successfully!');
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        session()->flash('success', 'Your account has been deleted.');
        return redirect()->route('home');
    }

    // --- Wishlist Management ---
    public function removeFromWishlist(int $propertyId)
    {
        $user = Auth::user();
        $user->favoriteProperties()->detach($propertyId);
        session()->flash('success', 'Property removed from wishlist.');
    }

    // --- Property Compare ---
    public function removeFromCompare(int $propertyId)
    {
        $this->compareList = array_values(array_diff($this->compareList, [$propertyId]));
        session()->put('compare_list', $this->compareList);
        session()->flash('success', 'Property removed from comparison.');
    }

    // --- Saved Searches ---
    public function deleteSavedSearch(int $id)
    {
        $search = SavedSearch::where('user_id', Auth::id())->where('id', $id)->first();
        if ($search) {
            $search->delete();
            session()->flash('success', 'Saved search deleted.');
        }
    }

    public function toggleSearchNotifications(int $id)
    {
        $search = SavedSearch::where('user_id', Auth::id())->where('id', $id)->first();
        if ($search) {
            $search->update([
                'notifications_enabled' => !$search->notifications_enabled
            ]);
        }
    }

    // --- Bookings ---
    public function cancelBooking(int $id)
    {
        $booking = PropertyBooking::where('user_id', Auth::id())->where('id', $id)->first();
        if ($booking) {
            $booking->update(['status' => 'cancelled']);
            session()->flash('success', 'Booking cancelled.');
        }
    }

    // --- Support Tickets ---
    public function createTicket()
    {
        $this->validate([
            'ticketSubject' => 'required|string|max:255',
            'ticketDescription' => 'required|string',
            'ticketCategory' => 'required|in:billing,technical,sales,general',
            'ticketPriority' => 'required|in:low,medium,high',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'ticket_number' => 'TCK-' . strtoupper(uniqid()),
            'subject' => $this->ticketSubject,
            'description' => $this->ticketDescription,
            'category' => $this->ticketCategory,
            'priority' => $this->ticketPriority,
            'status' => 'open',
        ]);

        $this->ticketSubject = '';
        $this->ticketDescription = '';
        
        session()->flash('success', 'Support ticket opened successfully: ' . $ticket->ticket_number);
    }

    public function selectTicket(int $ticketId)
    {
        $this->selectedTicket = SupportTicket::with('replies.user')->findOrFail($ticketId);
    }

    public function replyToTicket()
    {
        $this->validate([
            'replyMessage' => 'required|string',
        ]);

        SupportTicketReply::create([
            'support_ticket_id' => $this->selectedTicket->id,
            'user_id' => Auth::id(),
            'message' => $this->replyMessage,
        ]);

        // Keep status open or change to open if it was closed
        if ($this->selectedTicket->status === 'closed') {
            $this->selectedTicket->update(['status' => 'open']);
        }

        $this->replyMessage = '';
        $this->selectTicket($this->selectedTicket->id); // reload ticket

        session()->flash('success', 'Reply submitted.');
    }

    // --- Loan/EMI Calculator ---
    public function calculateEmi()
    {
        $principal = $this->loanAmount;
        $monthlyRate = ($this->interestRate / 12) / 100;
        $numberOfMonths = $this->loanTerm * 12;

        if ($monthlyRate > 0) {
            $this->emi = ($principal * $monthlyRate * pow(1 + $monthlyRate, $numberOfMonths)) / (pow(1 + $monthlyRate, $numberOfMonths) - 1);
        } else {
            $this->emi = $principal / $numberOfMonths;
        }

        $this->totalPayment = $this->emi * $numberOfMonths;
        $this->totalInterest = $this->totalPayment - $principal;
    }

    // --- KYC Upload ---
    public string $kycDocType = 'aadhaar';
    public string $kycDocNumber = '';
    public $kycFile;

    public function uploadKyc()
    {
        $this->validate([
            'kycFile' => 'required|file|mimes:jpeg,png,pdf|max:5120', // Max 5MB
            'kycDocType' => 'required|string',
            'kycDocNumber' => 'required|string|max:50',
        ]);

        $filePath = $this->kycFile->store('kyc', 'public');

        Auth::user()->kycDocuments()->create([
            'document_type' => $this->kycDocType,
            'document_number' => $this->kycDocNumber,
            'file_path' => '/storage/' . $filePath,
            'status' => 'pending',
        ]);

        $this->kycDocNumber = '';
        $this->kycFile = null;

        session()->flash('success', 'KYC Document uploaded successfully. Admin will verify shortly.');
    }

    public function render()
    {
        $user = Auth::user();

        // 1. Wishlist Properties
        $wishlist = $user->favoriteProperties()->with(['type', 'status', 'city', 'state'])->get();

        // 2. Compare Properties
        $compareProperties = Property::whereIn('id', $this->compareList)->with(['type', 'status', 'city', 'state', 'amenities'])->get();

        // 3. Saved Searches
        $savedSearches = $user->savedSearches()->latest()->get();

        // 4. Recently Viewed Properties (from session)
        $recentlyViewedIds = session()->get('recently_viewed_properties', []);
        $recentlyViewed = Property::whereIn('id', $recentlyViewedIds)->with(['type', 'status'])->limit(5)->get();

        // 5. Bookings
        $bookings = $user->bookings()->with('property')->latest()->get();

        // 6. Reviews
        $reviews = $user->comments()->with('blogPost')->latest()->get(); // User comments or Property reviews
        $propertyReviews = \App\Models\Review::where('user_id', $user->id)->with('property')->latest()->get();

        // 7. Support Tickets
        $tickets = $user->supportTickets()->latest()->get();

        // 8. Payments & Subscriptions
        $payments = Payment::where('user_id', $user->id)->with('subscriptionPlan')->latest()->get();

        // 9. KYC Uploads list
        $kycDocs = $user->kycDocuments()->latest()->get();

        return view('livewire.customer.customer-dashboard', [
            'wishlist' => $wishlist,
            'compareProperties' => $compareProperties,
            'savedSearches' => $savedSearches,
            'recentlyViewed' => $recentlyViewed,
            'bookings' => $bookings,
            'propertyReviews' => $propertyReviews,
            'tickets' => $tickets,
            'payments' => $payments,
            'kycDocs' => $kycDocs,
        ])->layout('layouts.app');
    }
}
