import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path'

export default defineConfig({
    // server: {
    //     host: '0.0.0.0', // Allow connections from any IP
    //     port: 5173,      // Or your preferred port
    //     strictPort: true,
    //     allowedHosts: ['.ngrok-free.app'],
    //     origin:"https://c995-129-205-124-246.ngrok-free.app",
    //     hmr: {
    //         host: 'localhost', // Or your Ngrok subdomain
    //       },
    //   },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            buildDirectory: 'build'
        }),
        tailwindcss(),
    ],
    build: {
        chunkSizeWarningLimit: 1000, // in KB, raise as needed
    }
});
