# Poetry Desktop App - Architecture Documentation

## System Overview

### Technology Stack
- **Frontend**: JavaScript, HTML5, CSS3
- **Backend**: PHP 8.2, Laravel 11
- **Database**: MySQL
- **Development Tools**: XAMPP, npm, composer

### Component Architecture

#### Frontend Components
```
frontend/
├── desktop/           # Desktop environment components
│   ├── icons/        # Icon management
│   ├── windows/      # Window management
│   └── menu/         # Menu bar system
├── poem/             # Poem-specific components
└── shared/           # Shared utilities
```

#### Backend Components
```
backend/
├── controllers/      # Request handlers
├── models/          # Data models
├── services/        # Business logic
└── database/        # Database migrations
```

### Core Components

#### Models
- `Poem`: Represents a poem with its content and desktop properties
- `User`: User model for authentication and ownership

#### Controllers
Located in `app/Http/Controllers`:
- Handles API endpoints for poem management
- Manages desktop state persistence
- Processes user interactions

### Project Structure
```
php_poem-desktop-app/
├── app/                    # Application core
│   ├── Http/              # Controllers and Middleware
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── bootstrap/             # App bootstrapping
├── config/               # Configuration files
├── database/            # Database migrations and seeds
├── docs/                # Project documentation
├── public/              # Public assets
├── resources/           # Frontend resources
│   ├── css/            # Stylesheets
│   ├── js/             # JavaScript files
│   ├── images/         # Image assets
│   └── views/          # Blade templates
├── routes/              # Route definitions
├── storage/            # Application storage
├── tests/              # Test files
├── vendor/             # Composer dependencies
├── .env                # Environment variables
├── .env.example        # Environment template
├── artisan             # Laravel CLI
├── composer.json       # PHP dependencies
├── package.json        # Node.js dependencies
├── tailwind.config.js  # Tailwind CSS config
└── vite.config.js      # Vite bundler config
```

### Data Flow
1. User interacts with desktop interface
2. Frontend components manage local state
3. API requests sent to backend
4. Backend processes and persists data
5. Response returned to frontend
6. UI updated accordingly

## Database Schema

### Poems Table
```sql
CREATE TABLE poems (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    icon_path VARCHAR(255) NULL,
    position_x INT DEFAULT 0,
    position_y INT DEFAULT 0,
    window_position_x INT NULL,
    window_position_y INT NULL,
    window_width INT NULL,
    window_height INT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Security Considerations

### Authentication
- CSRF protection enabled
- Session management
- Input validation and sanitization

### Data Protection
- SQL injection prevention
- XSS protection
- Secure file uploads

## Performance Optimizations

### Frontend
- Lazy loading of windows
- Event delegation
- Debounced position updates

### Backend
- Query optimization
- Response caching
- Batch operations

## Development Guidelines

### Code Organization
- Follow MVC pattern
- Use service classes for business logic
- Implement repository pattern for data access

### Best Practices
- Write self-documenting code
- Follow SOLID principles
- Implement proper error handling
- Write comprehensive tests

## Deployment Architecture

### Development
- Local XAMPP environment
- Hot reloading enabled
- Debug mode active

### Production
- Apache/Nginx server
- PHP-FPM
- MySQL optimization
- Cache layer
