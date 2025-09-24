<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;

class Dashboard extends PageWithDashboard // 👈 extiende la base
{
    public function render(): View
    {
        return view('livewire.dashboard'); // tu vista con un solo <div> root
    }
}
