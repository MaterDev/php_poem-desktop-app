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
        // Set initial position from data attributes, with default values
        const x = parseInt(window.dataset.x) || 50;
        const y = parseInt(window.dataset.y) || 50;
        const width = parseInt(window.dataset.width) || 400;
        const height = parseInt(window.dataset.height) || 300;
        
        window.style.left = `${x}px`;
        window.style.top = `${y}px`;
        window.style.width = `${width}px`;
        window.style.height = `${height}px`;
    });

    // Window dragging functionality
    document.querySelectorAll('.window').forEach(window => {
        const title = window.querySelector('.window-title');
        let isDragging = false;
        let initialX;
        let initialY;

        title.addEventListener('mousedown', startDragging);
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', stopDragging);

        window.addEventListener('click', () => {
            bringWindowToFront(window);
        });

        function startDragging(event) {
            if (window.dataset.isMaximized) return; // Prevent dragging if maximized
            
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
                
                // Save window position if it's not maximized
                if (!window.dataset.isMaximized) {
                    const poemId = window.dataset.poemId;
                    // Get the current position and ensure it's a number
                    const x = parseInt(window.style.left);
                    const y = parseInt(window.style.top);
                    
                    // Update the dataset to match the new position
                    window.dataset.x = x;
                    window.dataset.y = y;
                    
                    fetch(`/poems/${poemId}/window-position`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ x, y })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Window position saved:', { x, y });
                        }
                    })
                    .catch(error => console.error('Error updating window position:', error));
                }
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
                document.removeEventListener('mousemove', resize);
                document.removeEventListener('mouseup', stopResizing);

                // Save the new size to the database
                const poemId = window.dataset.poemId;
                const width = parseInt(window.style.width);
                const height = parseInt(window.style.height);

                // Update the dataset with new size
                window.dataset.width = width;
                window.dataset.height = height;
                
                // Store as original (non-maximized) size
                window.dataset.originalWidth = `${width}px`;
                window.dataset.originalHeight = `${height}px`;

                fetch(`/poems/${poemId}/window-size`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ width, height })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Window size saved:', { width, height });
                    }
                })
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
        icon.addEventListener('dblclick', () => openWindow(icon.dataset.poemId));

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
    });

    function openWindow(poemId) {
        const window = document.querySelector(`.window[data-poem-id="${poemId}"]`);
        if (window) {
            // Get the saved position and size from data attributes
            const x = parseInt(window.dataset.x) || 50;
            const y = parseInt(window.dataset.y) || 50;
            const width = parseInt(window.dataset.width) || 200;  
            const height = parseInt(window.dataset.height) || 150;  
            
            // Ensure window is not maximized
            delete window.dataset.isMaximized;
            const maximizeButton = window.querySelector('.maximize-button');
            if (maximizeButton) {
                maximizeButton.classList.remove('is-maximized');
            }
            
            // Store the current size as the original (non-maximized) size
            window.dataset.originalWidth = `${width}px`;
            window.dataset.originalHeight = `${height}px`;
            window.dataset.originalLeft = `${x}px`;
            window.dataset.originalTop = `${y}px`;
            
            // Set window size and position from saved values
            window.style.width = `${width}px`;
            window.style.height = `${height}px`;
            window.style.left = `${x}px`;
            window.style.top = `${y}px`;
            
            // Enable resize functionality
            const resizeHandle = window.querySelector('.window-resize');
            if (resizeHandle) {
                resizeHandle.style.pointerEvents = 'auto';
            }
            
            window.style.display = 'block';
            bringWindowToFront(window);
        }
    }

    // Window control buttons functionality
    document.querySelectorAll('.window-controls').forEach(controls => {
        const window = controls.closest('.window');
        const closeButton = controls.querySelector('.close-button');
        const maximizeButton = controls.querySelector('.maximize-button');

        closeButton.addEventListener('click', () => window.style.display = 'none');
        maximizeButton.addEventListener('click', () => {
            const desktop = document.querySelector('.desktop');
            const desktopRect = desktop.getBoundingClientRect();
            
            if (!window.dataset.isMaximized) {
                window.dataset.originalWidth = window.style.width;
                window.dataset.originalHeight = window.style.height;
                window.dataset.originalLeft = window.style.left;
                window.dataset.originalTop = window.style.top;
                
                // Fill the desktop area completely
                window.style.width = `${desktopRect.width}px`;
                window.style.height = `${desktopRect.height}px`;
                window.style.left = '0';
                window.style.top = '0';
                window.dataset.isMaximized = 'true';
                maximizeButton.classList.add('is-maximized');
                
                // Disable resize functionality
                const resizeHandle = window.querySelector('.window-resize');
                if (resizeHandle) {
                    resizeHandle.style.pointerEvents = 'none';
                }
            } else {
                window.style.width = window.dataset.originalWidth;
                window.style.height = window.dataset.originalHeight;
                window.style.left = window.dataset.originalLeft;
                window.style.top = window.dataset.originalTop;
                delete window.dataset.isMaximized;
                maximizeButton.classList.remove('is-maximized');
                
                // Re-enable resize functionality
                const resizeHandle = window.querySelector('.window-resize');
                if (resizeHandle) {
                    resizeHandle.style.pointerEvents = 'auto';
                }
            }
        });
    });
});