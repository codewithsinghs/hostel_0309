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


// import AdminLayout from "../layouts/AdminLayout.vue";
// import Dashboard from "../pages/admin/Dashboard.vue";

// export default [
//   {
//     path: "/admin",
//     component: AdminLayout,
//     children: [
//       {
//         path: "",
//         name: "admin.dashboard",
//         component: Dashboard,
//         meta: { requiresAuth: true },
//       },
//         ],
//   },
// ];



// import AdminLayout from "../layouts/AdminLayout.vue";

// function resourceRoutes(name, basePath) {
//   const base = `../pages/admin/${name}`;
//   return {
//     path: basePath,
//     children: [
//       {
//         path: "",
//         name: `admin.${name}.index`,
//         component: () => import(`${base}/Index.vue`),
//       },
//       {
//         path: "create",
//         name: `admin.${name}.create`,
//         component: () => import(`${base}/Create.vue`),
//       },
//       {
//         path: ":id",
//         name: `admin.${name}.view`,
//         props: true,
//         component: () => import(`${base}/View.vue`),
//       },
//       {
//         path: ":id/edit",
//         name: `admin.${name}.edit`,
//         props: true,
//         component: () => import(`${base}/Edit.vue`),
//       },
//     ],
//   };
// }

// export default [
//   {
//     path: "/admin",
//     component: AdminLayout,
//     meta: { requiresAuth: true },
//     children: [
//       {
//         path: "",
//         name: "admin.dashboard",
//         component: () => import("../pages/admin/Dashboard.vue"),
//       },
//       resourceRoutes("users", "users"),
//       resourceRoutes("reports", "reports"),
//     ],
//   },
// ];


import AdminLayout from "../layouts/AdminLayout.vue";

// ✅ Resource route generator
function resourceRoutes(name, basePath) {
  const base = `../pages/admin/${name}`;
  return {
    path: basePath,
    children: [
      {
        path: "",
        name: `admin.${name}.index`,
        component: () => import(/* @vite-ignore */ `${base}/Index.vue`),
      },
      {
        path: "create",
        name: `admin.${name}.create`,
        component: () => import(/* @vite-ignore */ `${base}/Create.vue`),
      },
      {
        path: ":id",
        name: `admin.${name}.view`,
        props: true,
        component: () => import(/* @vite-ignore */ `${base}/View.vue`),
      },
      {
        path: ":id/edit",
        name: `admin.${name}.edit`,
        props: true,
        component: () => import(/* @vite-ignore */ `${base}/Edit.vue`),
      },
    ],
  };
}

// ✅ Export routes
export default [
  {
    path: "/admin",
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        name: "admin.dashboard",
        component: () => import("../pages/admin/Dashboard.vue"),
      },
      resourceRoutes("users", "users"),
      resourceRoutes("reports", "reports"),
      resourceRoutes("blogs", "blogs"),
      resourceRoutes("posts", "posts"),
      resourceRoutes("events", "events"),
    ],
  },
];

