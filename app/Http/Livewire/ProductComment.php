<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductComment extends Component
{
    use WithPagination;
    protected $commentsData;
    public $comments = [];
    public $comment;
    public $product;
    public $reply;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function updatedComment()
    {
        $this->validate([
            'comment' => 'required|min:3',
        ]);
    }

    public function loadComments()
    {
        $this->comments = $this->product->comments()
            // ->latest()
            ->whereNull('parent_id')
            // ->with(['user', 'replies'])
            ->with('user')
            ->offset(0)
            ->limit(10)
            ->get();
        // ->paginate(10);

        // $this->comments = $this->commentsData->items();
    }

    public function addReply($id)
    {
        $this->validate([
            'reply' => 'required|min:3',
        ]);

        $this->saveComment($id);
        $this->reset(['reply']);
        $this->dispatchBrowserEvent('notify', ['title' => 'Reply added successfully!']);
        // $this->refresh();
        $this->loadComments();
    }

    public function saveComment($id = null)
    {
        $this->product->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $id ? $this->reply : $this->comment,
            'parent_id' => $id,
        ]);
    }

    public function addComment()
    {
        $this->validate([
            'comment' => 'required|min:3',
        ]);
        $this->saveComment();
        $this->reset(['comment']);
        $this->dispatchBrowserEvent('notify', ['title' => 'Comment added successfully!']);
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.product-comment');
    }
}
