<!-- <!-- <template>
    <div>
        <SiteHeader />

        <main>
            <router-view />
        </main>

        <SiteFooter />
    </div>
</template>

<script>
import SiteHeader from "../components/Header.vue";
import SiteFooter from "../components/Footer.vue";

export default {
    components: { SiteHeader, SiteFooter },
};
</script> -->

<script>
import 'bootstrap-icons/font/bootstrap-icons.css';

</script>



<template>
    <div class="d-flex" id="admin-layout">
      <!-- Sidebar -->
      <aside class="bg-dark text-white vh-100 p-3" style="width: 250px">
        <h3 class="text-center mb-4">Admin Panel</h3>
        <ul class="nav flex-column">
          <li class="nav-item mb-2">
            <router-link to="/admin" class="nav-link text-white">Dashboard</router-link>
          </li>
          <li class="nav-item mb-2">
            <router-link to="/admin/users" class="nav-link text-white">Users</router-link>
          </li>
          <li class="nav-item mb-2">
            <router-link to="/admin/reports" class="nav-link text-white">Reports</router-link>
          </li>
          <li class="nav-item mb-2">
            <router-link to="/admin/blogs" class="nav-link text-white">Blogs</router-link>
          </li>
          <li class="nav-item mb-2">
            <router-link to="/admin/posts" class="nav-link text-white">Posts</router-link>
          </li>
          <li class="nav-item mb-2">
            <router-link to="/admin/events" class="nav-link text-white">Events</router-link>
          </li>
        </ul>
      </aside>
  
      <!-- Main Content -->
      <main class="flex-fill p-4">
        This is Admin layout
        <!-- 👇 This is where child pages load -->
        <router-view />
      </main>
    </div>
  </template>
   -->