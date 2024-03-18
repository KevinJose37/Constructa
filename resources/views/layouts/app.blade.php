<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="show">
    @livewireScripts
    <!-- Topbar -->
    @include('Templates.topbar')
    <!-- Topbar -->

    <!-- Sidebar -->
    @include('Templates.sidebar')
    <!-- Sidebar -->
    <!-- Page Heading -->

    <!-- Page Content -->
    <main>
        <div class="wrapper">
            <div class="content-page">
                <div class="content">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    @stack('modals')

    
    <script src="assets/js/vendor.min.js"></script>
    <!-- Flatpickr Timepicker Plugin js -->
    <script src="assets/vendor/flatpickr/flatpickr.min.js"></script>
    <!-- Timepicker Demo js -->
    <script src="assets/js/pages/demo.flatpickr.js"></script>

</body>

</html>
