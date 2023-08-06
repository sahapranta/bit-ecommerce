<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductReview extends Component
{
    public $product;
    public $reviews;
    public $review;
    public $is_anonymous = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->reviews = collect([]);
    }

    public function addReview()
    {
        $this->validate([
            'review' => 'required|min:6',
        ]);

        $this->product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => 5,
            'review' => $this->review,
            'is_approved' => true,
            'is_anonymous' => $this->is_anonymous,
        ]);

        $this->reset(['review', 'is_anonymous' => false]);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'title' => 'Review added successfully!']);
        $this->loadReviews();
    }

    public function loadReviews()
    {
        $this->reviews = $this->product->reviews()
            ->approved()
            ->latest()
            ->with('user')
            ->limit(15)
            ->get();
    }

    public function render()
    {
        return view('livewire.product-review');
    }
}
