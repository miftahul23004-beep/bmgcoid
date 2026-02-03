import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import compression from 'vite-plugin-compression';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        // Gzip compression for production
        compression({
            algorithm: 'gzip',
            ext: '.gz',
            threshold: 1024, // Only compress files larger than 1KB
        }),
        // Brotli compression for modern browsers
        compression({
            algorithm: 'brotliCompress',
            ext: '.br',
            threshold: 1024,
        }),
    ],
    build: {
        // Enable minification
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
                drop_debugger: true,
            },
        },
        // Code splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate Alpine.js into its own chunk
                    alpine: ['alpinejs', '@alpinejs/intersect', '@alpinejs/collapse'],
                },
                // Optimize chunk file names
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
        // Enable source maps for debugging (optional, disable in production if needed)
        sourcemap: false,
        // CSS code splitting
        cssCodeSplit: true,
        // Optimize CSS
        cssMinify: true,
        // Target modern browsers for smaller bundle
        target: 'es2020',
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['alpinejs', '@alpinejs/intersect', '@alpinejs/collapse'],
    },
});
