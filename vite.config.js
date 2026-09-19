import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                'resources/css/app.css',
                'resources/css/background-slider.css',
                'resources/css/dashboard-admin.css',
                'resources/css/visitor-welcome.css',

                // JS
                'resources/js/app.js',
                'resources/js/background-slider.js',
                'resources/js/books-data.js',
                'resources/js/borrowers-data.js',
                'resources/js/dashboard-admin.js',
                'resources/js/landing.js',
                'resources/js/report.js',
                'resources/js/visitor-qr.js',
                'resources/js/visitor-register.js',
            ],

            refresh: true,

            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),

        tailwindcss(),
    ],

    server: {
        watch: {
            ignored: [
                '**/storage/framework/views/**',
            ],
        },
    },
});