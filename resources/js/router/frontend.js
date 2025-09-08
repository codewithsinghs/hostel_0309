// import DefaultLayout from "../layouts/DefaultLayout.vue";

// import Home from "../pages/Home.vue";
// import About from "../pages/About.vue";
// import Contact from "../pages/Contact.vue";

// export default {
//     path: "/",
//     component: DefaultLayout,
//     children: [
//         { path: "", name: "home", component: Home },
//         { path: "about", name: "about", component: About },
//         { path: "contact", name: "contact", component: Contact },
//     ],
// };


import DefaultLayout from "../layouts/DefaultLayout.vue";
import Home from "../pages/Home.vue";
import About from "../pages/About.vue";
import Contact from "../pages/Contact.vue";
import GuestRegister from "../pages/guest/GuestRegister.vue";
import AccessoryList from "../components/AccessoryList.vue";

export default [
  {
    path: "/",
    component: DefaultLayout,
    children: [
      { path: "", name: "home", component: Home },
      { path: "about", name: "about", component: About },
      { path: "contact", name: "contact", component: Contact },
      { path: "accessories", name: "accessory-list", component: AccessoryList },
      { path: "guest/registration", name: "guest-registration", component: GuestRegister },

    ],
  },
];
