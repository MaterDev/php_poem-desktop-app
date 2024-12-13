<!DOCTYPE html>
<html>

<head>

    @vite(['resources/css/index.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Poetry Desktop App</title>

</head>

<body style="background-image: url('{{ asset('images/background.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">        <div id="menu-bar" class="menu-bar">
        <img src="{{ asset('images/logo.png') }}" class="apple-menu" alt="logo">
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
            style="display: none; left: {{ $poem->window_position_x }}px; top: {{ $poem->window_position_y }}px; width: {{ $poem->window_width }}px; height: {{ $poem->window_height }}px;"
            data-poem-id="{{ $poem->id }}">
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

                        // calculate new position
                        const newX = event.clientX - initialX;
                        const newY = event.clientY - initialY;

                        // constrain to viewport
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

            // WIndow resize functionality
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

                    // set minimum size
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

                        // Send window size to database
                        fetch(`/poems/${poemId}/window-size`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    width,
                                    height
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Window size updated:', data);
                            })
                            .catch(error => {
                                console.error('Error updating window size:', error);
                            });
                    }
                }
            })

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
                    if (event.target === icon || event.target.classList.contains('icon-image') || event.target.classList.contains('icon-title')) {

                        isDragging = true;
                        initialX = event.clientX - icon.offsetLeft;
                        initialY = event.clientY - icon.offsetTop;
                        console.log(`Start dragging icon ${icon.dataset.poemId}`);
                    }
                }

                function drag(event) {
                    if (isDragging) {
                        event.preventDefault();
                       
                        // calculate new position
                        newX = event.clientX - initialX;
                        newY = event.clientY - initialY;

                        // constrain to viewport
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