import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',           // Principal CSS/SCSS
                'resources/js/app.js',              // Principal JS
                'resources/assets/css/demo.css',    // Otros archivos CSS
                'resources/js/laravel-user-management.js',
                'resources/js/planes-precontractual.js',
                'resources/js/pages/auth/reset-password.js'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
