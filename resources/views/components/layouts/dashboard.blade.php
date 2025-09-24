<x-layouts.app.dashboard-sidebar>
  <flux:main container class="flex flex-col">
    <div>
      {{ $slot }}
    </div>

    {{-- Footer global --}}
    @include('partials.footer')
  </flux:main>
</x-layouts.app.dashboard-sidebar>

