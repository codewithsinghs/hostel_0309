import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
// import DefaultLayout from "./layouts/DefaultLayout.vue";

// Bootstrap, jQuery, SweetAlert if needed
// import 'bootstrap/dist/css/bootstrap.min.css'
// import 'bootstrap'
// import "../front/css/index.css"; // your overrides after

// ✅ Bootstrap & CSS
// import 'bootstrap/dist/css/bootstrap.min.css';
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// // ✅ jQuery (attach to window so old scripts can use it)
// import $ from 'jquery';
// window.$ = $;
// window.jQuery = $;

// // ✅ SweetAlert2
// import Swal from 'sweetalert2';
// window.Swal = Swal;



import axios from "axios";

// axios.defaults.baseURL = "/api";
// axios.interceptors.request.use((config) => {
//   const token = localStorage.getItem("auth_token");
//   if (token) {
//     config.headers.Authorization = `Bearer ${token}`;
//   }
//   return config;
// });

const app = createApp(App)
app.use(router)
app.mount('#app')

// import { createApp } from 'vue'
// import App from './App.vue'
// import router from './router'

// createApp(App)
//   .use(router)
//   .mount('#app')

