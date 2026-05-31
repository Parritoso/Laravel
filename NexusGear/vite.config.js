import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const devServerHost = process.env.VITE_DEV_SERVER_HOST || '127.0.0.1';
const devServerPort = Number(process.env.VITE_DEV_SERVER_PORT || 5173);
const hmrHost = process.env.VITE_DEV_SERVER_HMR_HOST || 'localhost';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/app.scss',
                'resources/css/admin.scss',
                'resources/css/checkout.scss',
                'resources/css/home.scss',
                'resources/css/login.scss',
                'resources/css/onboarding.scss',
                'resources/css/password-view.scss',
                'resources/js/app.js',
                'resources/js/checkout.js',
                'resources/js/discounts_form.js',
                'resources/js/login.js',
                'resources/js/onboarding.js',
                'resources/js/password-view.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: devServerHost,
        port: devServerPort,
        hmr: {
            host: hmrHost,
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
