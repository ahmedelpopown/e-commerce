<?php

namespace App\Livewire\Customer;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.front-end-layout')]
class OrderDetails extends Component
{
    public function render()
    {
        return view('livewire.customer.order-details');
    }
}
