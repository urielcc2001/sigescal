<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;

class Dashboard extends PageWithDashboard
{
    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
