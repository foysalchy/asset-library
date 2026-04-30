<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="description" content="Explore our Asset">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ $setup->title ?? 'Intel' }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="">
     <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        media="print" onload="this.media='all' rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        media="print" onload="this.media='all'">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
     @include('frontend.partials.header')

    <!-- Page Content Area -->
    <main class="bg-[#f9f9fb]  ">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('frontend.partials.footer')

</body>

@stack('scripts')

</html>
