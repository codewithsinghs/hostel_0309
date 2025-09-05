// import AdminLayout from "../layouts/AdminLayout.vue";

// import Dashboard from "../pages/admin/Dashboard.vue";
// import Users from "../pages/admin/Users.vue";

// export default {
//   path: "/admin",
//   component: AdminLayout,
//   meta: { requiresAuth: true },
//   children: [
//     { path: "dashboard", name: "admin.dashboard", component: Dashboard },
//     { path: "users", name: "admin.users", component: Users },
//   ],
// };


import AdminLayout from "../layouts/AdminLayout.vue";
import Dashboard from "../pages/admin/Dashboard.vue";

export default [
  {
    path: "/admin",
    component: AdminLayout,
    children: [
      { path: "", name: "admin.dashboard", component: Dashboard, meta: { requiresAuth: true } },
    ],
  },
];
