import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import dotenv from "dotenv";
import path from "path";

dotenv.config();

export default defineConfig(() => {
    const isProduction = process.env.APP_ENV === "production";

    return {
        plugins: [
            laravel({
                input: ["resources/css/app.css", "resources/js/app.ts"], // Entry points for CSS and TypeScript files
                refresh: process.env.APP_DEBUG === "true", // Enable live-reloading (HMR)
            }),
            vue(),
        ],
        /* Configuration for the build process */
        build: {
            outDir: process.env.ASSET_BUILD_PATH, //Output directory for built assets
            manifest: true, // Generate a manifest file for Laravel to map assets
            sourcemap: !isProduction, // Enable source maps in development for easier debugging
            chunkSizeWarningLimit: 1600, // Increase chunk size warning limit to handle larger files
            cssCodeSplit: true, // Split CSS into separate files instead of inlining it into JS
            terserOptions: {
                compress: {
                    drop_console: isProduction, // Remove console logs in production
                    drop_debugger: isProduction, // Remove debugger statements in production
                    unused: true, // Remove unused variables/functions
                    annotations: false, // Strip comments/annotations
                },
            },
        },
        /* Development server configuration */
        server: {
            host: process.env.VITE_SERVER_HOST || "localhost", // Host for the dev server
            port: parseInt(process.env.VITE_SERVER_PORT) || 5173, // Port for the dev server
            open: false, // Do not open the browser automatically
            hmr: {
                host: process.env.VITE_SERVER_HOST || "localhost", // Host for Hot Module Replacement (HMR)
            },
            verbose: true, // Enable verbose logs for the dev server
        },
        /* Module resolution configuration*/
        resolve: {
            alias: {
                "@": path.resolve(__dirname, "resources/js"),
                "@assets": path.resolve(__dirname, "resources/assets"),
            },
        },
        /* Optimization options for pre-bundling dependencies */
        optimizeDeps: {
            include: ["vue", "@vue/runtime-dom"], // Explicitly include these dependencies for faster builds
        },
    };
});
