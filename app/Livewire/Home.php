<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    public $count = 0;

    public function increment()
    {
        abort_unless(Auth::check(), 403);
        $this->count++;
    }

    public function render()
    {
        return view('livewire.home');
    }
}
