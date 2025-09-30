<?php

namespace App\Livewire\Settings;

use Illuminate\Contracts\View\View;
use App\Livewire\PageWithDashboard;

class Appearance extends PageWithDashboard 
{
    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.settings.appearance');
    }
}
