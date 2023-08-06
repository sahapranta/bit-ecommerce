<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>OneUI - Bootstrap 5 Admin Template &amp; UI Framework</title>

    <meta name="description" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    <!-- Modules -->
    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])

    <!-- Alternatively, you can also include a specific color theme after the main stylesheet to alter the default color theme of the template -->
    {{-- @vite(['resources/sass/main.scss', 'resources/sass/oneui/themes/amethyst.scss', 'resources/js/oneui/app.js']) --}}
</head>

<body>
    <div id="page-container">
        <!-- Main Container -->
        <main id="main-container">
            <div class="hero-static d-flex align-items-center">
                <div class="content">
                    <!-- Page Content -->
                    @yield('content')
                    <!-- END Page Content -->
                    <div class="fs-sm text-muted text-center">
                        <strong>{{ config('app.name') }}</strong> &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </main>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!-- jQuery (required for jQuery Validation plugin) -->
    <!-- <script src="{{asset('js/lib/jquery.min.js')}}"></script> -->
    <!-- Page JS Plugins -->
    <!-- <script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script> -->


    @yield('js')
</body>

</html>