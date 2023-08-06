<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>{{ AppSettings::get('site_name', 'Laravel') }} | {{ AppSettings::get('site_title', 'Welcome to our Digital Store!') }}</title>

  <meta name="description" content="{{ AppSettings::get('site_name', 'Laravel') }} {{ AppSettings::get('site_title', 'Welcome to our Digital Store!') }}">
  <meta name="author" content="pixelcave">
  <meta name="robots" content="noindex, nofollow">

  <!-- Icons -->
  <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
  <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

  <!-- Modules -->
  @yield('css')
  @vite(['resources/sass/frontend.scss', 'resources/js/app.js', 'resources/js/frontend.js'])

  <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
  {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}
  @livewireStyles
  @stack('styles')
</head>

<body>
  <div id="page-container">
    <main id="main-container">
      @include('frontend.partials.navbar')
      <!-- Page Content -->
      @yield('content')
      @include('frontend.partials.footer')
    </main>
  </div>
  <!-- END Page Container -->
  @yield('js')
  @livewireScripts
  @stack('scripts')
  <script>
    window.addEventListener('notify', event => {
      toastr[event.detail?.type || 'info'](event.detail?.message || '', event.detail?.title || '', event.detail?.options || {})
    })

    <?php if (session()->has('message')) : ?>
      window.addEventListener('load', function() {
        toastr['<?= session()->get('alert') ?>']("<?= session()->get('message') ?>")
      })
    <?php endif; ?>
  </script>
</body>

</html>