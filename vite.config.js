import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: [
            '**/storage/**',
            '**/bootstrap/cache/**',
            '**/vendor/**',
            '**/node_modules/**',
            '**/database/**',
            '**/*.sqlite',
        ],
            usePolling: true,
            interval: 300,
        },
        hmr: {
            overlay: true, 
        },
    },
});
