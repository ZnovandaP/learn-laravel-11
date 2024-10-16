<x-navbar />

<section class="container px-4">
  <header>
    {{ $header ?? 'Hello Dunia' }}
  </header>

  {{ $slot }}
  @stack('scripts')
</section>
