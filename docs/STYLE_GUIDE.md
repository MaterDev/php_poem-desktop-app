# Poetry Desktop App - Style Guide

## Code Style Guidelines

### PHP

#### Naming Conventions
- **Classes**: PascalCase (e.g., `PoemController`)
- **Methods**: camelCase (e.g., `updatePosition`)
- **Variables**: camelCase (e.g., `$poemContent`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_WINDOW_SIZE`)
- **Files**: PascalCase for classes, lowercase for views

#### Code Structure
```php
<?php

namespace App\Controllers;

use App\Models\Poem;

class PoemController extends Controller
{
    // Properties at the top
    private $poemService;

    // Constructor next
    public function __construct(PoemService $poemService)
    {
        $this->poemService = $poemService;
    }

    // Public methods
    public function store(Request $request): JsonResponse
    {
        // Method implementation
    }

    // Private methods last
    private function validatePoem(array $data): bool
    {
        // Validation logic
    }
}
```

### JavaScript

#### Naming Conventions
- **Functions**: camelCase (e.g., `openWindow`)
- **Variables**: camelCase (e.g., `windowPosition`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `DEFAULT_WINDOW_SIZE`)
- **Files**: camelCase (e.g., `desktopManager.js`)

#### Code Structure
```javascript
// Imports at the top
import { WindowManager } from './WindowManager';

// Constants next
const DEFAULT_SIZE = 200;

// Class/function definitions
class DesktopManager {
    constructor() {
        this.windows = new Map();
    }

    // Public methods first
    openWindow(id) {
        // Implementation
    }

    // Private methods last
    #calculatePosition() {
        // Implementation
    }
}
```

### CSS/SCSS

#### Naming Conventions
- Use kebab-case for classes
- BEM methodology for component classes
- Prefix utility classes with `u-`

#### Structure
```scss
// Component
.poem-window {
    &__header {
        // Header styles
    }

    &__content {
        // Content styles
    }

    &--minimized {
        // Modifier styles
    }
}

// Utilities
.u-hidden {
    display: none !important;
}
```

## File Organization

### Directory Structure
```
app/
├── Http/
│   ├── Controllers/    # Request handlers
│   └── Middleware/     # Request middleware
├── Models/            # Eloquent models
├── Services/          # Business logic
└── Repositories/      # Data access
```

### Frontend Structure
```
resources/
├── js/
│   ├── app.js         # Main application entry
│   ├── bootstrap.js   # JavaScript bootstrap
│   └── desktop.js     # Desktop environment logic
├── css/
│   └── app.css        # Main stylesheet
└── views/
    └── *.blade.php    # Blade templates
```

## Documentation

### PHP DocBlocks
```php
/**
 * Updates the position of a poem's window.
 *
 * @param Request $request The incoming request
 * @param Poem $poem The poem to update
 * @return JsonResponse
 * @throws ValidationException
 */
public function updateWindowPosition(Request $request, Poem $poem)
```

### JavaScript Comments
```javascript
/**
 * Creates a new window for displaying a poem.
 * @param {number} poemId - The ID of the poem to display
 * @param {Object} position - The initial window position
 * @param {number} position.x - X coordinate
 * @param {number} position.y - Y coordinate
 * @returns {Window} The created window instance
 */
function createPoemWindow(poemId, position) {
```

## Git Commit Guidelines

### Commit Message Format
```
type(scope): subject

body

footer
```

### Types
- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation
- **style**: Code style changes
- **refactor**: Code refactoring
- **test**: Testing
- **chore**: Maintenance

### Examples
```
feat(window): add window resize functionality

- Implement resize handles
- Add minimum size constraints
- Save window size to database

Closes #123
```

## Code Review Checklist

### General
- [ ] Code follows style guide
- [ ] Documentation is updated
- [ ] Tests are included
- [ ] No debugging code
- [ ] Error handling implemented

### Security
- [ ] Input is validated
- [ ] SQL injection prevented
- [ ] XSS prevention implemented
- [ ] CSRF protection used

### Performance
- [ ] Queries are optimized
- [ ] Proper indexing used
- [ ] Caching implemented
- [ ] Assets are optimized
