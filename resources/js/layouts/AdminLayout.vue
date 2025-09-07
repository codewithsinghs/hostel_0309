<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import "bootstrap-icons/font/bootstrap-icons.css";
import * as bootstrap from "bootstrap";
import { useAuthStore } from "@/stores/auth";

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();

// Sidebar open/close
const isSidebarOpen = ref(true);

// Menu items
const menuItems = [
  { name: "Dashboard", icon: "bi-speedometer2", to: "/admin" },
  { name: "Users", icon: "bi-people", to: "/admin/users" },
  { name: "Reports", icon: "bi-graph-up", to: "/admin/reports" },
  { name: "Blogs", icon: "bi-journal-text", to: "/admin/blogs" },
  { name: "Posts", icon: "bi-pencil-square", to: "/admin/posts" },
  { name: "Events", icon: "bi-calendar-event", to: "/admin/events" },
];

// Notifications
const notifications = ref([
  { id: 1, text: "New user registered", time: "2m ago" },
  { id: 2, text: "Report generated", time: "10m ago" },
  { id: 3, text: "System alert: Check logs", time: "1h ago" },
]);

// Logout
const handleLogout = async () => {
  try {
    await auth.logout();
    router.push("/login");
  } catch (err) {
    console.error("Logout failed", err);
  }
};

// Toggle sidebar
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

// Bootstrap JS for dropdowns/tooltips
onMounted(() => {
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
  document.querySelectorAll('.dropdown-toggle').forEach(el => new bootstrap.Dropdown(el));
});
</script>

<style scoped>
.luxury-admin {
  min-height: 100vh;
  font-family: "Inter", sans-serif;
}

/* Sidebar */
aside {
  background-color: #000;
  color: #fff;
  height: 100vh;
  transition: all 0.3s;
  overflow-y: auto;
  z-index: 1050;
}

.sidebar-open {
  width: 250px;
}

.sidebar-collapsed {
  width: 70px;
}

.nav-link {
  color: #bbb;
  transition: all 0.3s;
}

.nav-link:hover {
  color: #fff;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 0.5rem;
}

.nav-link.active {
  background: linear-gradient(90deg, #d4af37, #f1c40f);
  color: #000 !important;
  font-weight: 600;
  border-radius: 0.5rem;
}

/* Navbar */
.luxury-navbar {
  background: linear-gradient(90deg, #2c3e50, #34495e);
}

main {
  transition: margin-left 0.3s;
  min-height: calc(100vh - 56px - 40px);
}

/* Responsive: keep same behavior on all screens */
@media (max-width: 576px) {
  aside.sidebar-open,
  aside.sidebar-collapsed {
    height: 100vh;
    position: fixed;
  }

  main {
    margin-left: auto !important;
  }
}
</style>

<template>
  <div class="luxury-admin d-flex">
    <!-- Sidebar -->
    <aside :class="[isSidebarOpen ? 'sidebar-open' : 'sidebar-collapsed', 'p-3 shadow-lg']">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 v-if="isSidebarOpen" class="mb-0 fw-bold">Admin</h4>
        <button class="btn btn-sm btn-outline-light border-0" @click="toggleSidebar">
          <i class="bi" :class="isSidebarOpen ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
      </div>

      <ul class="nav flex-column">
        <li v-for="item in menuItems" :key="item.to" class="nav-item mb-2">
          <router-link :to="item.to" class="nav-link d-flex align-items-center fw-medium"
            :class="{ active: route.path.startsWith(item.to) }">
            <i :class="['bi me-2 fs-5', item.icon]"></i>
            <span v-if="isSidebarOpen">{{ item.name }}</span>
          </router-link>
        </li>
      </ul>
    </aside>

    <!-- Main content -->
    <!-- <div class="flex-grow-1 d-flex flex-column" :style="{ marginLeft: isSidebarOpen ? '250px' : '70px' }"> -->
        <div class="flex-grow-1 d-flex flex-column" >
      <!-- Navbar -->
      <nav class="navbar luxury-navbar navbar-expand-lg px-3 shadow-sm d-flex justify-content-end align-items-center">
        <div class="d-flex align-items-center gap-3">

          <!-- Notifications -->
          <div class="dropdown">
            <a class="text-white position-relative dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="bi bi-bell fs-5"></i>
              <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li v-if="notifications.length === 0" class="dropdown-item text-center text-muted">No notifications</li>
              <li v-for="n in notifications" :key="n.id" class="dropdown-item">
                <div>{{ n.text }}</div>
                <small class="text-muted">{{ n.time }}</small>
              </li>
            </ul>
          </div>

          <!-- Profile -->
          <div class="dropdown">
            <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <img src="https://via.placeholder.com/40" class="rounded-circle me-2" />
              <span v-if="isSidebarOpen">Admin</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <button @click="handleLogout" class="dropdown-item text-danger fw-semibold">
                  <i class="bi bi-box-arrow-right"></i> Logout
                </button>
              </li>
            </ul>
          </div>

        </div>
      </nav>

      <!-- Page content -->
      <main class="p-4 flex-grow-1 bg-light position-relative">
        <router-view />
      </main>

      <!-- Footer -->
      <footer class="bg-white text-center py-2 shadow-sm small text-muted">
        © {{ new Date().getFullYear() }} Admin Panel — All rights reserved
      </footer>
    </div>
  </div>
</template>
