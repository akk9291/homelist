<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PropertyReviews extends Component
{
    public int $propertyId;
    public int $rating = 5;
    public string $comment = '';

    protected array $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:5|max:1000',
    ];

    public function mount(int $propertyId)
    {
        $this->propertyId = $propertyId;
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to submit a review.');
            return;
        }

        $this->validate();

        $existing = Review::where('user_id', Auth::id())
            ->where('property_id', $this->propertyId)
            ->first();

        if ($existing) {
            $existing->update([
                'rating' => $this->rating,
                'comment' => $this->comment,
            ]);
            session()->flash('success', 'Your review has been updated!');
        } else {
            Review::create([
                'user_id' => Auth::id(),
                'property_id' => $this->propertyId,
                'rating' => $this->rating,
                'comment' => $this->comment,
            ]);
            session()->flash('success', 'Your review has been posted successfully!');
        }

        $this->comment = '';
        $this->rating = 5;
    }

    public function render()
    {
        $property = Property::with(['reviews.user'])->find($this->propertyId);
        $reviews = $property->reviews()->latest()->get();
        $averageRating = $reviews->avg('rating') ?: 0;

        return view('livewire.property-reviews', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
        ]);
    }
}
