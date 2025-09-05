import { createRouter, createWebHistory } from 'vue-router'

// Import your page components
import Home from '../pages/Home.vue'
import Contact from '../pages/Contact.vue'
import AccessoryList from '../components/AccessoryList.vue'
import About from "../pages/About.vue";
// import DefaultLayout from "../layouts/DefaultLayout.vue";

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/contact', name: 'contact', component: Contact },
  { path: "/about", name: "about", component: About },
  { path: '/accessories', name: 'accessory-list', component: AccessoryList },
  
  // Import your page components


]

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
})

export default router


