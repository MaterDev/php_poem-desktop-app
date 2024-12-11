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
        // Basic dragging functionality
        document.querySelectorAll('.window').forEach(window => {
            const title = window.querySelector('.window-title');
            let isDragging = false;
            let currentX;
            let currentY;
            let initialX;
            let initialY;

            title.addEventListener('mousedown', startDragging);
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDragging);

            function startDragging(event) {
                isDragging = true;
                initialX = event.clientX - window.offsetLeft;
                initialY = event.clientY - window.offsetTop;
            }

            function drag(event) {
                if (isDragging) {
                    event.preventDefault();
                    currentX = event.clientX - initialX;
                    currentY = event.clientY - initialY;
                    window.style.left = `${currentX}px`;
                    window.style.top = `${currentY}px`;
                }
            }

            function stopDragging() {
                isDragging = false;
            }
        })
    </script>


</body>

</html>