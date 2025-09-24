<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>@include('partials.head')</head>
  <body class="min-h-screen bg-white dark:bg-zinc-800">
    @include('partials.site-header', ['showMobileMenu' => true])

    <flux:main container class="flex flex-col">
      <div>{{ $slot }}</div>
      @include('partials.footer')
    </flux:main>

    @fluxScripts
  </body>
</html>
