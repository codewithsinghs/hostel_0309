import { createRouter, createWebHistory } from "vue-router";

// Import your page components
import Home from "../pages/Home.vue";
// import GuestRegisteration from "../pages/guest/GuestRegister.vue";
// import GuestLogin from "../pages/guest/GuestLogin.vue";
import Contact from "../pages/Contact.vue";
import AccessoryList from "../components/AccessoryList.vue";
import About from "../pages/About.vue";
// import DefaultLayout from "../layouts/DefaultLayout.vue";

import Register from "../pages/auth/Register.vue";
import Login from "../pages/auth/Login.vue";

const routes = [
    { path: "/", name: "home", component: Home },
    // {
    //     path: "/guest/register",
    //     name: "guest-register",
    //     component: GuestRegisteration,
    // },
    // { path: "/guest/login", name: "guest-login", component: GuestLogin },
    { path: "/contact", name: "contact", component: Contact },
    { path: "/about", name: "about", component: About },
    { path: "/accessories", name: "accessory-list", component: AccessoryList },

    { path: "register", name: "register", component: Register },
    { path: "login", name: "login", component: Login },

    // Import your page components
];

// const routes = [
//   {
//     path: "/",
//     component: DefaultLayout,
//     children: [
//       { path: "", name: "home", component: Home },
//       { path: "contact", name: "contact", component: Contact },
//       { path: "about", name: "about", component: About },
//     ],
//   },
// ];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
