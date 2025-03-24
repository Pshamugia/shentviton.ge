import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
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
