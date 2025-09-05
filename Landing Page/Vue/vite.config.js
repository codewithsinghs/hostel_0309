// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ['resources/css/app.css', 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
// });

// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import vue from '@vitejs/plugin-vue';

// export default defineConfig({
//     plugins: [
//         laravel({
//             // ✅ include all your entrypoints
//             input: [
//                 'resources/front/css/common.css',
//                 'resources/front/css/index.css',
//                 'resources/front/css/register.css',
//                 'resources/front/css/bootstrap.css',

//                 'resources/js/app.js',
//                 'resources/front/js/bootstrap.bundle.js',
//                 'resources/front/js/jquery3-6-0min.js',
//                 'resources/front/js/sweetalert.js',
//                 'resources/front/js/style.js',
//             ],
//             refresh: true,
//         }),
//         vue(),
//     ],
// });

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/front/css/bootstrap.css",
                "resources/front/css/common.css",
                "resources/front/css/index.css",
                "resources/front/css/register.css",

                "resources/js/app.js",
                "resources/front/js/bootstrap.bundle.js",
                "resources/front/js/jquery3-6-0min.js",
                "resources/front/js/sweetalert.js",
                "resources/front/js/style.js",
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
