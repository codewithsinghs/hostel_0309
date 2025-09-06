import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
// import DefaultLayout from "./layouts/DefaultLayout.vue";

// Bootstrap, jQuery, SweetAlert if needed
// import 'bootstrap/dist/css/bootstrap.min.css'
// import 'bootstrap'
// import "../front/css/index.css"; // your overrides after

// import $ from 'jquery'
// window.$ = window.jQuery = $

const app = createApp(App)
app.use(router)
app.mount('#app')

// import { createApp } from 'vue'
// import App from './App.vue'
// import router from './router'

// createApp(App)
//   .use(router)
//   .mount('#app')

