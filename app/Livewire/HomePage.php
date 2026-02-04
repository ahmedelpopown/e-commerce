<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.front-end-layout')]

class HomePage extends Component
{
    public function render()
    {
         return view('livewire.home-page');
    }
}
