import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import { cpSync } from 'fs';
import path from 'path';

// Plugin to copy TinyMCE static assets after build (Vite clears public/build each time)
function copyTinymceAssets() {
    return {
        name: 'copy-tinymce',
        writeBundle() {
            const src = path.resolve('node_modules/tinymce');
            const dest = path.resolve('public/build/tinymce');
            ['skins', 'icons', 'models', 'themes', 'plugins'].forEach(dir => {
                cpSync(`${src}/${dir}`, `${dest}/${dir}`, { recursive: true, force: true });
            });
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/tinymce-editor.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
        copyTinymceAssets(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
