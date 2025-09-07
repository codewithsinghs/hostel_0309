<script setup>
import { ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import "bootstrap-icons/font/bootstrap-icons.css";
import { useAuthStore } from "@/stores/auth";

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const isSidebarOpen = ref(true);

// Sidebar menu items (dynamic + scalable)
// Later: filter based on auth.user.roles or permissions
const menuItems = [
  { name: "Dashboard", icon: "bi-speedometer2", to: "/admin" },
  { name: "Users", icon: "bi-people", to: "/admin/users" },
  { name: "Reports", icon: "bi-graph-up", to: "/admin/reports" },
  { name: "Blogs", icon: "bi-journal-text", to: "/admin/blogs" },
  { name: "Posts", icon: "bi-pencil-square", to: "/admin/posts" },
  { name: "Events", icon: "bi-calendar-event", to: "/admin/events" },
];

// ✅ Logout action (clean & professional)
const handleLogout = async () => {
  await auth.logout(); // clears token + user
  router.push("/login"); // redirect
};
</script>


<template>
  <div class="d-flex luxury-admin" id="admin-layout">
    <!-- Sidebar -->
    <aside :class="[
      'text-white p-3 position-fixed h-100 shadow-lg',
      isSidebarOpen ? 'sidebar-open' : 'sidebar-collapsed',
    ]">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 v-if="isSidebarOpen" class="mb-0 fw-bold">Admin</h4>
        <button class="btn btn-sm btn-outline-light border-0" @click="isSidebarOpen = !isSidebarOpen">
          <i class="bi" :class="isSidebarOpen ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
      </div>

      <ul class="nav flex-column">
        <li v-for="item in menuItems" :key="item.to" class="nav-item mb-2">
          <router-link :to="item.to" class="nav-link d-flex align-items-center fw-medium"
            :class="{ active: route.path === item.to }">
            <i :class="['bi me-2 fs-5', item.icon]"></i>
            <span v-if="isSidebarOpen">{{ item.name }}</span>
          </router-link>
        </li>
      </ul>
    </aside>

    <!-- Main Content -->
    <div class="flex-grow-1 d-flex flex-column" :style="{ marginLeft: isSidebarOpen ? '250px' : '70px' }">
      <!-- Top Navbar -->
      <nav class="navbar luxury-navbar navbar-expand-lg px-3 shadow-sm bg-dark text-white">
        <div class="ms-auto d-flex align-items-center gap-4">

          <!-- Notifications Icon -->
          <i class="bi bi-bell fs-5 text-white position-relative">
            <span
              class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
          </i>

          <!-- Profile Dropdown -->
          <div class="dropdown">
            <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <img src="https://via.placeholder.com/40" class="rounded-circle me-2 border border-2 border-gold"
                alt="Profile" />
              <span class="fw-semibold">Admin</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                  <i class="bi bi-person-circle"></i> Profile
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                  <i class="bi bi-gear"></i> Settings
                </a>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li>
                <button @click="handleLogout"
                  class="dropdown-item d-flex align-items-center gap-2 text-danger fw-semibold">
                  <i class="bi bi-box-arrow-right"></i> Logout
                </button>
              </li>
            </ul>
          </div>

        </div>
      </nav>


      <!-- Page Content -->
      <main class="p-4 flex-grow-1 bg-light">
        <router-view />
      </main>

      <!-- Footer -->
      <footer class="bg-white text-center py-2 shadow-sm small text-muted">
        © {{ new Date().getFullYear() }} Luxury Admin — All rights reserved
      </footer>
    </div>
  </div>
</template>

<style scoped>
/* Luxury color palette */
.luxury-admin aside {
  background: linear-gradient(180deg, #1c1c1c, #2c2c2c);
}

.luxury-navbar {
  background: linear-gradient(90deg, #1c1c1c, #2c2c2c);
  color: #f1c40f;
  /* gold accent */
}

.border-gold {
  border-color: #f1c40f !important;
}

.nav-link {
  color: #ddd;
  border-radius: 0.5rem;
  padding: 0.6rem 1rem;
  transition: all 0.2s;
}

.nav-link:hover {
  background: rgba(255, 215, 0, 0.2);
  color: #f1c40f !important;
}

.nav-link.active {
  background: #f1c40f;
  color: #1c1c1c !important;
}

.sidebar-open {
  width: 250px;
}

.sidebar-collapsed {
  width: 70px;
}

#admin-layout aside {
  top: 0;
  left: 0;
  transition: width 0.3s ease;
  z-index: 1030;
}
</style>
