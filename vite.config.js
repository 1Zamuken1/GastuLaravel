import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/nav-bar.css',
                'resources/js/app.js',
                'resources/js/nav-bar.js',
                'resources/css/chatbot.css', 
                'resources/js/chatbot.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});

