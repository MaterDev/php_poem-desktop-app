# Poetry Desktop App - UI Guidelines

## Design System

### Colors

#### Primary Colors
```scss
$primary-blue: #1a73e8;
$primary-dark: #1557b0;
$primary-light: #4285f4;
```

#### Secondary Colors
```scss
$secondary-gray: #5f6368;
$secondary-light: #bdc1c6;
$secondary-dark: #3c4043;
```

#### System Colors
```scss
$background: #ffffff;
$surface: #f8f9fa;
$error: #d93025;
$success: #1e8e3e;
$warning: #f9ab00;
```

#### Dark Mode Colors
```scss
$dark-background: #202124;
$dark-surface: #292a2d;
$dark-text: #e8eaed;
```

### Typography

#### Font Family
```css
--font-primary: 'Roboto', sans-serif;
--font-monospace: 'Roboto Mono', monospace;
```

#### Font Sizes
```scss
$font-xs: 0.75rem;    // 12px
$font-sm: 0.875rem;   // 14px
$font-base: 1rem;     // 16px
$font-lg: 1.125rem;   // 18px
$font-xl: 1.25rem;    // 20px
$font-2xl: 1.5rem;    // 24px
```

#### Font Weights
```scss
$font-normal: 400;
$font-medium: 500;
$font-bold: 700;
```

### Spacing

#### Base Units
```scss
$spacing-unit: 0.25rem;  // 4px

$space-1: $spacing-unit;     // 4px
$space-2: $spacing-unit * 2; // 8px
$space-3: $spacing-unit * 3; // 12px
$space-4: $spacing-unit * 4; // 16px
$space-6: $spacing-unit * 6; // 24px
$space-8: $spacing-unit * 8; // 32px
```

### Shadows
```scss
$shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
$shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
$shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
$shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
```

## Component Library

### Windows

#### Default Window
```html
<div class="window">
    <div class="window__titlebar">
        <div class="window__title">Poem Title</div>
        <div class="window__controls">
            <button class="window__control window__control--minimize">
            <button class="window__control window__control--maximize">
            <button class="window__control window__control--close">
        </div>
    </div>
    <div class="window__content">
        <!-- Content here -->
    </div>
</div>
```

#### Window States
- Normal
- Minimized
- Maximized
- Focused
- Dragging

### Desktop Icons

#### Icon Structure
```html
<div class="desktop-icon">
    <div class="desktop-icon__image">
        <!-- Icon image -->
    </div>
    <div class="desktop-icon__label">
        Poem Name
    </div>
</div>
```

#### Icon States
- Default
- Selected
- Dragging
- Disabled

### Buttons

#### Primary Button
```html
<button class="btn btn--primary">
    Create Poem
</button>
```

#### Secondary Button
```html
<button class="btn btn--secondary">
    Cancel
</button>
```

#### Button States
- Default
- Hover
- Active
- Disabled
- Loading

## Animation Guidelines

### Transitions
```scss
$transition-fast: 150ms ease-in-out;
$transition-normal: 250ms ease-in-out;
$transition-slow: 350ms ease-in-out;
```

### Window Animations
- Open: Fade in and scale up
- Close: Fade out and scale down
- Minimize: Scale down to taskbar
- Maximize: Scale up to full screen

### Icon Animations
- Drag start: Scale up slightly
- Drag end: Scale back to normal
- Selection: Highlight with fade

## Accessibility

### Keyboard Navigation
- Tab navigation between windows
- Arrow keys for icon selection
- Space/Enter to open
- Esc to close/cancel

### ARIA Attributes
```html
<!-- Example window -->
<div 
    role="dialog"
    aria-labelledby="window-title"
    aria-modal="true"
>
    <h2 id="window-title">Poem Title</h2>
</div>
```

### Focus Management
- Visible focus indicators
- Focus trap in modal windows
- Proper focus order
- Skip links where needed

### Screen Reader Support
- Meaningful alt text
- ARIA labels
- Role attributes
- Live regions

## Responsive Design

### Breakpoints
```scss
$breakpoint-sm: 640px;
$breakpoint-md: 768px;
$breakpoint-lg: 1024px;
$breakpoint-xl: 1280px;
```

### Mobile Adaptations
- Stack windows vertically
- Full-width containers
- Larger touch targets
- Simplified animations

### Touch Support
- Touch events for dragging
- Pinch-to-zoom
- Swipe gestures
- Touch feedback

## Icons and Assets

### Icon Sizes
```scss
$icon-sm: 16px;
$icon-base: 24px;
$icon-lg: 32px;
$icon-xl: 48px;
```

### Asset Guidelines
- SVG preferred
- 2x resolution for images
- Consistent padding
- Clear silhouettes

## Loading States

### Skeletons
```html
<div class="skeleton">
    <div class="skeleton__title"></div>
    <div class="skeleton__content"></div>
</div>
```

### Progress Indicators
- Spinner for short loads
- Progress bar for longer operations
- Skeleton screens for content

## Error States

### Error Messages
```html
<div class="error-message" role="alert">
    <span class="error-message__icon"></span>
    <span class="error-message__text">Error description</span>
</div>
```

### Visual Feedback
- Red highlights
- Error icons
- Shake animation
- Clear recovery options
