<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/index.css'])
    @vite(['resources/js/desktop.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Poetry Desktop App</title>
</head>
<body>
    <div id="menu-bar" class="menu-bar">
        <img class="apple-menu" src="{{ url('/resources/images/logo.png') }}" alt="Apple Menu">
        <span>File</span>
    </div>

    <div class="desktop">
        <!-- Desktop Icons -->
        @foreach($poems as $poem)
        <div class="desktop-icon"
            data-poem-id="{{ $poem->id }}"
            data-x="{{ $poem->icon_position_x }}"
            data-y="{{ $poem->icon_position_y }}">
            <div class="icon-image"></div>
            <div class="icon-title">{{ $poem->title }}</div>
        </div>
        @endforeach

        <!-- Windows (hidden by default)  -->
        @foreach($poems as $poem)
        <div class="window"
            data-poem-id="{{ $poem->id }}"
            data-x="{{ $poem->window_position_x }}"
            data-y="{{ $poem->window_position_y }}"
            data-width="{{ $poem->window_width }}"
            data-height="{{ $poem->window_height }}">
            <div class="window-controls">
                <div class="window-button close-button"></div>
                <div class="window-button minimize-button"></div>
            </div>
            <div class="window-title">{{$poem->title}}</div>
            <div class="window-content">
                {!! nl2br(e($poem->content)) !!}
            </div>
            <div class="window-resize"></div>
        </div>
        @endforeach
    </div>
</body>
</html>