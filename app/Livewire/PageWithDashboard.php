<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.dashboard')]
abstract class PageWithDashboard extends Component
{
    // Si quieres, aquí puedes poner helpers comunes para el panel.
}
