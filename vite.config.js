import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import path from 'path';

// bootstrap-icons.scss references its woff/woff2 files via url(), which Vite
// cannot statically resolve (they're built from a Sass variable), so the font
// binaries are silently left out of the production build. Copy them in manually
// so the @font-face rules the CSS emits actually have files to point at.
function copyBootstrapIconsFonts() {
    return {
        name: 'copy-bootstrap-icons-fonts',
        closeBundle() {
            const srcDir = path.resolve(__dirname, 'node_modules/bootstrap-icons/font/fonts');
            const destDir = path.resolve(__dirname, 'public/build/assets/fonts');
            if (!fs.existsSync(srcDir)) return;
            fs.mkdirSync(destDir, { recursive: true });
            for (const file of fs.readdirSync(srcDir)) {
                fs.copyFileSync(path.join(srcDir, file), path.join(destDir, file));
            }
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/sass/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        copyBootstrapIconsFonts(),
    ],
});
