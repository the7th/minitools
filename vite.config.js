import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    publicDir: false,
    plugins: [tailwindcss()],
    build: {
        outDir: "public/build",
        emptyOutDir: true,
        rollupOptions: {
            input: "resources/app.css",
            output: {
                assetFileNames: "app[extname]",
            },
        },
    },
});
