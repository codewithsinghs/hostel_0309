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


// import { defineConfig } from "vite";
// import vue from "@vitejs/plugin-vue";
// import laravel from "laravel-vite-plugin";


// export default defineConfig({
//     plugins: [
//         laravel({
//             input: [
//                 "resources/front/css/bootstrap.css",
//                 "resources/front/css/swiper-bundle.min.css",
//                 "resources/front/css/common.css",
//                 "resources/front/css/index.css",
//                 "resources/front/css/register.css",

//                 "resources/js/app.js",
//                 "resources/front/js/bootstrap.bundle.js",
//                 "resources/front/js/jquery3-6-0min.js",
//                 "resources/front/js/sweetalert.js",
//                 "resources/front/js/style.js",
//                 "resources/front/js/index.js",
//             ],
//             refresh: true,
//         }),
//         vue(),
//     ],
//     resolve: {
//         alias: {
//             vue: "vue/dist/vue.esm-bundler.js",
//         },
//     },
// });



import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/front/css/bootstrap.css",
                "resources/front/css/swiper-bundle.min.css",
                "resources/front/css/common.css",
                "resources/front/css/index.css",
                "resources/front/css/register.css",

                "resources/js/app.js",
                "resources/front/js/bootstrap.bundle.js",
                "resources/front/js/jquery3-6-0min.js",
                "resources/front/js/sweetalert.js",
                "resources/front/js/style.js",
                "resources/front/js/index.js",
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
});
