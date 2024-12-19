import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
    return glob.sync(query);
}

/**
 * Js Files
 */
// Page JS Files
const pageJsFiles = GetFilesArray('resources/assets/js/*.js');

// Processing Vendor JS Files
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js');

// Processing Libs JS Files
const LibsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

/**
 * Scss Files
 */
// Processing Core, Themes & Pages Scss Files
const CoreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');

// Processing Libs Scss & Css Files
const LibsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');

// Processing Fonts Scss Files
const FontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/!(_)*.scss');

// Processing Window Assignment for Libs like jKanban, pdfMake
function libsWindowAssignment() {
    return {
        name: 'libsWindowAssignment',

        transform(src, id) {
            if (id.includes('jkanban.js')) {
                return src.replace('this.jKanban', 'window.jKanban');
            } else if (id.includes('vfs_fonts')) {
                return src.replaceAll('this.pdfMake', 'window.pdfMake');
            }
        }
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/assets/css/demo.css',
                'resources/js/app.js',
                ...pageJsFiles,
                ...vendorJsFiles,
                ...LibsJsFiles,
                'resources/js/laravel-user-management.js', // Processing Laravel User Management CRUD JS File
                'resources/js/planes-precontractual.js',
                'resources/js/pages/auth/reset-password.js',
                ...CoreScssFiles,
                ...LibsScssFiles,
                ...LibsCssFiles,
                ...FontsScssFiles,
                'resources/css/app.scss',           // Principal CSS/SCSS
                'resources/js/app.js',              // Principal JS
                'resources/assets/css/demo.css',    // Otros archivos CSS
                'resources/js/laravel-user-management.js',
                'resources/js/planes-precontractual.js',
                'resources/js/pages/auth/reset-password.js',
                'resources\assets\vendor\libs\@form-validation\popular.js',
                'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
                'resources/assets/vendor/libs/@form-validation/auto-focus.js'
            ],
            refresh: true
        }),
        html(),
        libsWindowAssignment()
    ],
    build: {
        // ... otras configuraciones
        css: {
            preprocessorOptions: {
                scss: {
                    additionalData: `
                        @import "resources/assets/css/test-table.css";
                    `
                }
            }
        }
    }
});
