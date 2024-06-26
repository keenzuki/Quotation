<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Animation --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- jquery 3.5.1 cdn --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">

</head>

<body class="font-sans antialiased">
    @guest()
        <!-- Default App View -->
        <div class="container-xxl bg-white p-0">
            <!-- Header -->
            @include('layouts.navigation')
            <!-- Content -->
            @yield('content')
            <!-- Footer -->
            @yield('footer')
        </div>
    @else
        <!-- Admin Panel -->
        @if (Auth::user()->is_admin)
            @include('admin.navbar')
            @include('components.success')
            <div class="container-fluid">
                <div class="row flex-nowrap">
                    @include('admin.sidebar')
                    @yield('content')
                </div>
            </div>
            @yield('footer')
        @else
            @include('layouts.navigation')
            @include('components.success')
            <div class="container-fluid">
                <div class="row flex-nowrap">
                    @include('layouts.sidebar')
                    @yield('content')
                </div>
            </div>
            @yield('footer')
        @endif
    @endguest
    <script>
        setTimeout(() => {
            $('#alert').alert('close')
        }, 6000)
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/dataTable.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.js') }}"></script>
</body>

</html>
