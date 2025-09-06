import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Bootstrap, jQuery, SweetAlert if needed
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'
import $ from 'jquery'
window.$ = window.jQuery = $

const app = createApp(App)
app.use(router)
app.mount('#app')
