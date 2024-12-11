<!DOCTYPE html>
<html>

<head>

    @vite(['resources/css/index.css'])

    <title>Poetry Desktop App</title>

</head>

<body>
    <h1>Poetry Desktop App</h1>

    <div class="menu-bar">
        <!-- Menu items will go here -->
    </div>

    <div class="desktop">
        @foreach($poems as $poem)
        <div class="window poemWindow" style="top: '{{ $poem->position_y }}'px; left: '{{ $poem->position_x }}'px;">
            <div class="window-title">{{ $poem->title }}</div>
            <div class="window-content">
                {{ $poem->content }}
            </div>
        </div>
        @endforeach
    </div>

    <script>

    </script>


</body>

</html>