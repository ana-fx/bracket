<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-gray-50 text-slate-800 flex flex-col min-h-screen selection:bg-primary/30">

    @include('partials.header')

    <main class="flex-grow relative z-10">
        @yield('content')
    </main>


    @include('partials.footer')

</body>
</html>
