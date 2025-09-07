// import AuthLayout from "../layouts/AuthLayout.vue";

// import Login from "../pages/auth/Login.vue";
// import Register from "../pages/auth/Register.vue";

// export default {
//   path: "/auth",
//   component: AuthLayout,
//   children: [
//     { path: "login", name: "login", component: Login, meta: { guestOnly: true } },
//     { path: "register", name: "register", component: Register, meta: { guestOnly: true } },
//   ],
// };

import AuthLayout from "../layouts/AuthLayout.vue";
import DefaultLayout from "../layouts/DefaultLayout.vue";
import Login from "../pages/auth/Login.vue";
import Register from "../pages/auth/Register.vue";

export default [
  {
    path: "/login",
    component: DefaultLayout,
    children: [
      { path: "", name: "login", component: Login, meta: { guestOnly: true } },
    ],
  },
  {
    path: "/register",
    component: DefaultLayout,
    children: [
      { path: "", name: "register", component: Register, meta: { guestOnly: true } },
    ],
  },
];


// import Login from "../pages/auth/Login.vue";
// import Register from "../pages/auth/Register.vue";

// export default [
//   {
//     path: "/login",
//     name: "login",
//     component: Login,
//     meta: { guestOnly: true },
//   },
//   {
//     path: "/register",
//     name: "register",
//     component: Register,
//     meta: { guestOnly: true },
//   },
// ];
