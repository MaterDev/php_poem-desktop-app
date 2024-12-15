# Poetry Desktop App

A web application that presents poems in a desktop operating system interface. Built with Laravel and JavaScript, this app recreates the familiar feel of a classic operating system.

## Features

- 🖥️ Desktop-style user interface
- 📝 Poems displayed as desktop icons and windows
- 🖱️ Interactive window management system
- 🎨 Classic operating system design elements
- 📚 Poem management and organization

## Development Setup

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- XAMPP (or similar local development environment)
- Git

### Local Development

1. Clone the repository:
```bash
git clone https://github.com/MaterDev/php_poem-desktop-app.git
cd php_poem-desktop-app
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Environment Setup:
```bash
cp .env.example .env
php artisan key:generate
```

5. Database Setup:
- Create a new database in XAMPP's phpMyAdmin
- Update `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=poetry_desktop
DB_USERNAME=root
DB_PASSWORD=
```
- Run migrations:
```bash
php artisan migrate
```

6. Start Development Servers:
- Ensure XAMPP's Apache and MySQL services are running
- Start Vite development server:
```bash
npm run dev
```

7. Access the Application:
- Visit `http://localhost/php_poem-desktop-app/public` in your browser
- For development, you can also use Laravel's built-in server:
```bash
php artisan serve
```
Then visit `http://localhost:8000`
