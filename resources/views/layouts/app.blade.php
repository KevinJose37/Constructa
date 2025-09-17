@props(['title'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-menu-color="light">

<head data-menu-color="light">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0d6efd">
    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" id="app-style">

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <!-- Styles -->
    @livewireStyles
</head>

<body>
    @livewireScripts
    <!-- Page Heading -->

    <!-- Page Content -->
    <div class="wrapper">
        <!-- Topbar -->
        @include('Templates.topbar')
        <!-- Topbar -->

        <!-- Sidebar -->
        @include('Templates.sidebar')
        <!-- Sidebar -->
        <div class="content-page">
            <div class="content">
                {{ $slot }}
            </div>
        </div>
    </div>


    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <!-- Flatpickr Timepicker Plugin js -->
    <script src="{{ asset('assets/vendor/flatpickr/flatpickr.min.js') }}"></script>
    <link href="{{ asset('assets/vendor/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <!-- Timepicker Demo js -->
    <script src="{{ asset('assets/js/pages/demo.flatpickr.js') }}"></script>
    {{-- App script --}}
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    @once
        @push('scripts')
            <script src="{{ asset('assets/vendor/flatpickr/l10n/es.js') }}"></script>
        @endpush
    @endonce

    @stack('scripts')

    <script>
  if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/service-worker.js")
      .then(() => console.log("Service Worker registrado"))
      .catch(err => console.error("Error al registrar SW:", err));
  }
</script>


</body>

</html>
