<?php

namespace App\Livewire\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('components.layouts.front-end-layout')]
class Profile extends Component
{
    public function render()
    {
        return view('livewire.customer.profile');
    }
}
