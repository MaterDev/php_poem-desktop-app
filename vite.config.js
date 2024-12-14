import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/index.css',
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/desktop.js',
                'resources/images/logo.png'
            ],
            refresh: true,
        }),
    ],
});
