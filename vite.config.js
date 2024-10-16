import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias:{
            '@': path.resolve(__dirname + '/resources/js'),
            '@modules' : path.resolve(__dirname + '/Modules')
        },
    },
    server: {
        hmr: {
            host: process.env.APP_URL 
        },
    }

});
