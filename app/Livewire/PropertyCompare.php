<?php

namespace App\Livewire;

use App\Models\Property;
use Livewire\Component;

class PropertyCompare extends Component
{
    public array $propertyIds = [];

    public function mount()
    {
        $this->propertyIds = session()->get('compare_list', []);
    }

    public function removeProperty(int $id)
    {
        $this->propertyIds = array_diff($this->propertyIds, [$id]);
        session()->put('compare_list', $this->propertyIds);
    }

    public function clearCompare()
    {
        $this->propertyIds = [];
        session()->forget('compare_list');
    }

    public function render()
    {
        $properties = Property::whereIn('id', $this->propertyIds)
            ->with(['type', 'status', 'city', 'state', 'amenities'])
            ->get();

        return view('livewire.property-compare', [
            'properties' => $properties,
        ])->layout('layouts.app');
    }
}
