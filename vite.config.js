import { defineConfig } from "vite";
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    define: {
        // enable this for debug in build mode
        __VUE_PROD_DEVTOOLS__: process.env.NODE_ENV === 'development'
    },
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false
                },
            },
        })
    ],
    build: {
        assetsDir: '',
        rollupOptions: {
            input: ['resources/js/app.js', 'resources/sass/style.scss'],
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            },
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
