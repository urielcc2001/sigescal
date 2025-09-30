<?php

namespace App\Livewire\Settings;

use Illuminate\Contracts\View\View;
use App\Livewire\PageWithDashboard; 

class Locale extends PageWithDashboard
{
    public string $locale = '';

    public function mount(): void
    {
        $this->locale = auth()->user()->locale;
    }

    public function updateLocale(): void
    {
        $this->validate([
            'locale' => 'required|string|in:en,da,es',
        ]);

        auth()->user()->update([
            'locale' => $this->locale,
        ]);

        $this->dispatch('locale-updated', name: auth()->user()->name);
    }

    #[Layout('components.layouts.app')]
    public function render(): View
    {
        return view('livewire.settings.locale', [
            'locales' => [
                'en' => 'English',
                'da' => 'Danish',
                'es' => 'Español',
            ],
        ]);
    }
}
