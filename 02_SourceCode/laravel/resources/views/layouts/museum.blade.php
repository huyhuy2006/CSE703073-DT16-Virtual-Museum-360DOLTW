<!DOCTYPE html>
<html lang="{{ $locale ?? $lang ?? 'vi' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#10243a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DT-16 Virtual Museum 360°')</title>
    @stack('head')
    @yield('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>