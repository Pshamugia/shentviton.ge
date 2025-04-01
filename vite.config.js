import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    base: '/build/', // ⬅️ THIS is the missing part for production
    plugins: [
        laravel({
            input: [
                "resources/css/sass/app.scss",
                "resources/css/app.css",
                "resources/css/tabmenu.css",
                "resources/js/app.js",
                "resources/js/product.js",
            ],
            refresh: true,
        }),
    ],
});
