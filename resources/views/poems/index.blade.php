<!DOCTYPE html>
<html>
<head>
    @vite(['resources/css/index.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Poetry Desktop App</title>
</head>
<body>
    <div id="menu-bar" class="menu-bar">
        <img class="apple-menu" alt="logo" src="{{ url('resources/images/logo.png') }}">
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

    <script>
        function constrainToViewport(x, y, width, height) {
            const viewport = {
                width: window.innerWidth,
                height: window.innerHeight - 20
            }

            return {
                x: Math.min(Math.max(x, 0), viewport.width - width),
                y: Math.min(Math.max(y, 0), viewport.height - height)
            } 
        }

        document.addEventListener('DOMContentLoaded', function() {
            let topZIndex = 1000;

            function bringWindowToFront(window) {
                topZIndex++;
                window.style.zIndex = topZIndex;
                document.getElementById('menu-bar').style.zIndex = topZIndex + 1;
            }

            // Position desktop icons and windows on load
            document.querySelectorAll('.desktop-icon').forEach(icon => {
                icon.style.left = icon.dataset.x + 'px';
                icon.style.top = icon.dataset.y + 'px';
            });

            document.querySelectorAll('.window').forEach(window => {
                window.style.left = window.dataset.x + 'px';
                window.style.top = window.dataset.y + 'px';
                window.style.width = window.dataset.width + 'px';
                window.style.height = window.dataset.height + 'px';
            });

            // Basic dragging functionality for windows
            document.querySelectorAll('.window').forEach(window => {
                window.style.zIndex = 1000;
                const title = window.querySelector('.window-title');
                let isDragging = false;
                let initialX;
                let initialY;

                title.addEventListener('mousedown', startDragging);
                document.addEventListener('mousemove', drag);
                document.addEventListener('mouseup', stopDragging);

                window.addEventListener('mousedown', () => {
                    bringWindowToFront(window);
                });

                function startDragging(event) {
                    if (event.target === title) {
                        event.preventDefault();
                        isDragging = true;
                        initialX = event.clientX - window.offsetLeft;
                        initialY = event.clientY - window.offsetTop;
                    }
                }

                function drag(event) {
                    if (isDragging) {
                        event.preventDefault();
                        const newX = event.clientX - initialX;
                        const newY = event.clientY - initialY;
                        const constrained = constrainToViewport(newX, newY, window.offsetWidth, window.offsetHeight);
                        window.style.left = `${constrained.x}px`;
                        window.style.top = `${constrained.y}px`;
                    }
                }

                function stopDragging() {
                    if (isDragging) {
                        isDragging = false;
                        const poemId = window.dataset.poemId;
                        const x = window.offsetLeft;
                        const y = window.offsetTop;

                        fetch(`/poems/${poemId}/window-position`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ x, y })
                        })
                        .then(response => response.json())
                        .then(data => console.log('Window position updated:', data))
                        .catch(error => console.error('Error updating window position:', error));
                    }
                }
            });

            // Window resize functionality
            document.querySelectorAll('.window').forEach(window => {
                let isResizing = false;
                let initialWidth;
                let initialHeight;
                let initialX;
                let initialY;

                const resizeHandle = window.querySelector('.window-resize');

                resizeHandle.addEventListener('mousedown', startResizing);
                document.addEventListener('mousemove', resize);
                document.addEventListener('mouseup', stopResizing);

                function startResizing(event) {
                    isResizing = true;
                    initialWidth = window.offsetWidth;
                    initialHeight = window.offsetHeight;
                    initialX = event.clientX;
                    initialY = event.clientY;
                    event.preventDefault();
                }

                function resize(event) {
                    if (!isResizing) return;
                    const newWidth = initialWidth + (event.clientX - initialX);
                    const newHeight = initialHeight + (event.clientY - initialY);
                    if (newWidth >= 200 && newHeight >= 150) {
                        window.style.width = newWidth + 'px';
                        window.style.height = newHeight + 'px';
                    }
                }
                
                function stopResizing() {
                    if (isResizing) {
                        isResizing = false;
                        const poemId = window.dataset.poemId;
                        const width = window.offsetWidth;
                        const height = window.offsetHeight;

                        fetch(`/poems/${poemId}/window-size`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ width, height })
                        })
                        .then(response => response.json())
                        .then(data => console.log('Window size updated:', data))
                        .catch(error => console.error('Error updating window size:', error));
                    }
                }
            });

            // Desktop icon functionality
            document.querySelectorAll('.desktop-icon').forEach(icon => {
                let isDragging = false;
                let initialX;
                let initialY;

                icon.addEventListener('mousedown', startDragging);
                document.addEventListener('mousemove', drag);
                document.addEventListener('mouseup', stopDragging);
                icon.addEventListener('dblclick', openWindow);

                function startDragging(event) {
                    if (event.target === icon || event.target.classList.contains('icon-image') || event.target.classList.contains('icon-title')) {
                        isDragging = true;
                        initialX = event.clientX - icon.offsetLeft;
                        initialY = event.clientY - icon.offsetTop;
                        event.preventDefault();
                    }
                }

                function drag(event) {
                    if (isDragging) {
                        event.preventDefault();
                        const newX = event.clientX - initialX;
                        const newY = event.clientY - initialY;
                        const constrained = constrainToViewport(newX, newY, icon.offsetWidth, icon.offsetHeight);
                        icon.style.left = `${constrained.x}px`;
                        icon.style.top = `${constrained.y}px`;
                    }
                }

                function stopDragging() {
                    if (isDragging) {
                        isDragging = false;
                        const poemId = icon.dataset.poemId;
                        const x = icon.offsetLeft;
                        const y = icon.offsetTop;

                        fetch(`/poems/${poemId}/icon-position`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ x, y })
                        })
                        .then(response => response.json())
                        .then(data => console.log('Position updated:', data))
                        .catch(error => console.error('Error updating position:', error));
                    }
                }

                function openWindow() {
                    const poemId = icon.dataset.poemId;
                    const window = document.querySelector(`.window[data-poem-id="${poemId}"]`);
                    if (window) {
                        window.style.display = 'block';
                        bringWindowToFront(window);
                    }
                }
            });

            // Window control buttons functionality
            document.querySelectorAll('.window-controls').forEach(controls => {
                const window = controls.closest('.window');
                const closeButton = controls.querySelector('.close-button');
                const minimizeButton = controls.querySelector('.minimize-button');

                closeButton.addEventListener('click', () => window.style.display = 'none');
                minimizeButton.addEventListener('click', () => window.style.display = 'none');
            });
        });
    </script>
</body>
</html>