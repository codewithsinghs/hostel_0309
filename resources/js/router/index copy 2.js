// import { createRouter, createWebHistory } from "vue-router";

// // Layouts
// import DefaultLayout from "../layouts/DefaultLayout.vue";

// // Pages
// import Home from "../pages/Home.vue";
// import About from "../pages/About.vue";
// import Contact from "../pages/Contact.vue";
// import Register from "../pages/Register.vue";
// import Login from "../pages/Login.vue";
// import Dashboard from "../pages/Dashboard.vue";

// const routes = [
//   {
//     path: "/",
//     component: DefaultLayout,
//     children: [
//       { path: "", name: "home", component: Home },
//       { path: "about", name: "about", component: About },
//       { path: "contact", name: "contact", component: Contact },
//       { path: "register", name: "register", component: Register, meta: { guestOnly: true } },
//       { path: "login", name: "login", component: Login, meta: { guestOnly: true } },
//       { path: "dashboard", name: "dashboard", component: Dashboard, meta: { requiresAuth: true } },
//     ],
//   },
//   // Fallback for unknown routes
//   {
//     path: "/:pathMatch(.*)*",
//     redirect: "/",
//   },
// ];

// const router = createRouter({
//   history: createWebHistory(),
//   routes,
// });

// // 🔹 Route Guard for Authentication
// router.beforeEach((to, from, next) => {
//   const isAuthenticated = !!localStorage.getItem("auth_token");

//   if (to.meta.requiresAuth && !isAuthenticated) {
//     // If route requires login & user not logged in → redirect to login
//     return next({ name: "login" });
//   }

//   if (to.meta.guestOnly && isAuthenticated) {
//     // If route is guest-only & user already logged in → redirect to dashboard
//     return next({ name: "dashboard" });
//   }

//   next();
// });

// export default router;

import { createRouter, createWebHistory } from "vue-router";

// Import grouped routes
import frontendRoutes from "./frontend";
import adminRoutes from "./admin";
import authRoutes from "./auth";

const routes = [
    frontendRoutes,
    adminRoutes,
    authRoutes,
    { path: "/:pathMatch(.*)*", redirect: "/" }, // 404 fallback
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// 🔑 Auth guard
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem("auth_token");

    if (to.meta.requiresAuth && !isAuthenticated) {
        return next({ name: "login" });
    }
    if (to.meta.guestOnly && isAuthenticated) {
        return next({ name: "admin.dashboard" });
    }

    next();
});

export default router;
