<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/index.css'])
    @vite(['resources/js/desktop.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Poetry Desktop App</title>
</head>
<body>
    @include('components.menu-bar')
    
    <div class="desktop">
        @yield('content')
    </div>
</body>
</html>
