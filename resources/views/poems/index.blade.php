<!DOCTYPE html>
<html>

<head>

    @vite(['resources/css/index.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Poetry Desktop App</title>

</head>

<body>
    <h1>Poetry Desktop App</h1>

    <div id="menu-bar" class="menu-bar">
        <div class="apple-menu"></div>
        <span>File</span>
    </div>

    <div class="desktop">

        <!-- Desktop Icons -->
        @foreach($poems as $poem)
        <div class="desktop-icon"
            data-poem-id="{{ $poem->id }}"
            data-x="{{ $poem->icon_position_x }}"
            data-y="{{ $poem->icon_position_y }}"
            style="left: {{ $poem->icon_position_x }}px; top: {{ $poem->icon_position_y }}px;">
            <div class="icon-image"></div>
            <div class="icon-title">{{ $poem->title }}</div>
        </div>

        @endforeach


        <!-- Windows (hidden by default)  -->
        @foreach($poems as $poem)
        <div class="window"
            style="display: none; left: {{ $poem->window_position_x }}px; top: {{ $poem->window_position_y }}px;"
            data-poem-id="{{ $poem->id }}">
            <div class="window-controls">
                <div class="window-button close-button"></div>
                <div class="window-button minimize-button"></div>
            </div>
            <div class="window-title">{{$poem->title}}</div>
            <div class="window-content">
                {!! nl2br(e($poem->content)) !!}
            </div>
        </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Basic dragging functionality for windows
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
                    if (isDragging) {
                        isDragging = false;
                        const poemId = window.dataset.poemId;
                        const x = window.offsetLeft;
                        const y = window.offsetTop;

                        // Send window position to server
                        fetch(`/poems/${poemId}/window-position`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    x,
                                    y
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Window position updated:', data);
                            })
                            .catch(error => {
                                console.error('Error updating window position:', error);
                            });
                    }
                }
            });

            // When icon is double-clicked, will open the corresponding window
            document.querySelectorAll('.desktop-icon').forEach(icon => {
                icon.addEventListener('dblclick', () => {
                    const poemId = icon.dataset.poemId;
                    const window = document.querySelector(`.window[data-poem-id="${poemId}"]`);
                    if (window) {
                        window.style.display = 'block';
                        // Use the saved positions from the database
                        const savedTop = window.style.top || '50px';
                        const savedLeft = window.style.left || '50px';
                        window.style.top = savedTop;
                        window.style.left = savedLeft;
                        bringWindowToFront(window);
                    }
                });
            });

            // When close button is selected, will make window invisible
            document.querySelectorAll('.close-button').forEach(button => {
                button.addEventListener('click', () => {
                    const window = button.closest('.window');
                    if (window) {
                        window.style.display = 'none';
                    }
                });
            });

            // Make icons draggable and position them based on database values
            document.querySelectorAll('.desktop-icon').forEach(icon => {
                let isDragging = false;
                let currentX;
                let currentY;
                let initialX;
                let initialY;

                // Set initial position based on database values
                const x = parseInt(icon.dataset.x) || 0;
                const y = parseInt(icon.dataset.y) || 0;
                icon.style.left = `${x}px`;
                icon.style.top = `${y}px`;
                console.log(`Icon ${icon.dataset.poemId} initial position: x=${x}, y=${y}`);

                icon.addEventListener('mousedown', startDragging);
                document.addEventListener('mousemove', drag);
                document.addEventListener('mouseup', stopDragging);

                function startDragging(event) {
                    isDragging = true;
                    initialX = event.clientX - icon.offsetLeft;
                    initialY = event.clientY - icon.offsetTop;
                    console.log(`Start dragging icon ${icon.dataset.poemId}`);
                }

                function drag(event) {
                    if (isDragging) {
                        event.preventDefault();
                        currentX = event.clientX - initialX;
                        currentY = event.clientY - initialY;
                        icon.style.left = `${currentX}px`;
                        icon.style.top = `${currentY}px`;
                    }
                }

                function stopDragging() {
                    if (isDragging) {
                        isDragging = false;
                        const poemId = icon.dataset.poemId;
                        const x = icon.offsetLeft;
                        const y = icon.offsetTop;

                        // Send position to server
                        fetch(`/poems/${poemId}/icon-position`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    x,
                                    y
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Position updated:', data)
                            })
                            .catch(error => {
                                console.error('Error updating position:', error);
                            });

                        console.log(`Stopped dragging icon ${icon.dataset.poemId}. New position: x=${icon.offsetLeft}, y=${icon.offsetTop}`);
                    }
                }
            });

            let topZIndex = 1000; // Higher number means closer to foreground

            function bringWindowToFront(window) {
                topZIndex++;
                window.style.zIndex = topZIndex;

                // Keep menu bar above all other things
                document.getElementById('menu-bar').style.zIndex = topZIndex + 1
            }

            document.querySelectorAll('.window').forEach(window => {
                // Set initial z-index
                window.style.zIndex = 1000;

                // Bring to front when the window is clicked elsewhere
                window.addEventListener('mousedown', () => {
                    bringWindowToFront(window);
                })
            })
        });
    </script>


</body>

</html>