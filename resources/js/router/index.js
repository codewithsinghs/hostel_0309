// import { createRouter, createWebHistory } from "vue-router";

// // Import grouped routes
// import frontendRoutes from "./frontend";
// import adminRoutes from "./admin";
// import authRoutes from "./auth";

// const routes = [
//   ...frontendRoutes,
//   ...adminRoutes,
//   ...authRoutes,
//   { path: "/:pathMatch(.*)*", redirect: "/" },
// ];


// const router = createRouter({
//     history: createWebHistory(),
//     routes,
// });

// // 🔑 Auth guard
// router.beforeEach((to, from, next) => {
//     const isAuthenticated = !!localStorage.getItem("auth_token");

//     if (to.meta.requiresAuth && !isAuthenticated) {
//         return next({ name: "login" });
//     }
//     if (to.meta.guestOnly && isAuthenticated) {
//         return next({ name: "admin.dashboard" });
//     }

//     next();
// });

// export default router;

import { createRouter, createWebHistory } from "vue-router";

// Import grouped routes
import frontendRoutes from "./frontend";
import adminRoutes from "./admin";
import authRoutes from "./auth";

// Merge all routes
const routes = [
  ...frontendRoutes,
  ...adminRoutes,
  ...authRoutes,
  { path: "/:pathMatch(.*)*", redirect: "/" }, // fallback
];

// Create router
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Debugging helper: log every route navigation
router.beforeEach((to, from, next) => {
  console.log("Navigating from:", from.fullPath, "to:", to.fullPath);
  
  const isAuthenticated = !!localStorage.getItem("auth_token");

  if (to.meta.requiresAuth && !isAuthenticated) {
    console.warn("Route requires auth, redirecting to login:", to.fullPath);
    return next({ name: "login" });
  }

  if (to.meta.guestOnly && isAuthenticated) {
    console.warn("Guest-only route, redirecting to dashboard:", to.fullPath);
    return next({ name: "admin.dashboard" });
  }

  next();
});

// Global error handling for navigation failures
router.onError((error) => {
  console.error("Router error:", error);
});

// Optional: log after each navigation success
router.afterEach((to, from) => {
  console.log("Successfully navigated to:", to.fullPath);
});

export default router;
