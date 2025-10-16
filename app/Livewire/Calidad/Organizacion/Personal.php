<?php

namespace App\Livewire\Calidad\Organizacion;

use Illuminate\Contracts\View\View;
use App\Livewire\PageWithDashboard;

class Personal extends PageWithDashboard
{

    public function mount(): void
    {
        //
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.calidad.organizacion.personal');
    }
}
