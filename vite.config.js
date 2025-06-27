import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        chunkSizeWarningLimit: 1000, // sets new limit to 1000kb
        outDir: 'public/build',
        manifest: true,
        rollupOptions: {
            input: resolve(__dirname, 'resources/js/main.js')
        }
      },
});
