<template>
  <div class="d-flex" id="admin-dashboard">
    <!-- Sidebar -->
    <aside class="bg-dark text-white vh-100 p-3" style="width: 250px;">
      <h3 class="text-center mb-4">Admin Panel</h3>
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <router-link to="/admin" class="nav-link text-white">Dashboard</router-link>
        </li>
        <li class="nav-item mb-2">
          <router-link to="#" class="nav-link text-white">Users</router-link>
        </li>
        <li class="nav-item mb-2">
          <router-link to="#" class="nav-link text-white">Reports</router-link>
        </li>
        <li class="nav-item mt-5">
          <button class="btn btn-danger w-100" @click="logout">Logout</button>
        </li>
      </ul>
    </aside>

    <!-- Main Content -->
    <main class="flex-fill p-4">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <div>
          <span class="me-3">Hello, {{ user?.name }}</span>
        </div>
      </div>

      <!-- Metrics Cards -->
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card text-white bg-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Users</h5>
              <p class="card-text">{{ metrics.users }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-success mb-3">
            <div class="card-body">
              <h5 class="card-title">Active Sessions</h5>
              <p class="card-text">{{ metrics.sessions }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card text-white bg-warning mb-3">
            <div class="card-body">
              <h5 class="card-title">Pending Requests</h5>
              <p class="card-text">{{ metrics.requests }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity Table -->
      <div class="card">
        <div class="card-header">Recent Users</div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in recentUsers" :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.created_at }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import axios, { setAuthToken } from "../../services/axios";

export default {
  name: "Dashboard",
  data() {
    return {
      user: null,
      loading: true,
      metrics: {
        users: 0,
        sessions: 0,
        requests: 0,
      },
      recentUsers: [],
    };
  },
  async created() {
    try {
      const { data } = await axios.get("/ndashboard");
      this.user = data.user;
      this.metrics = data.metrics || { users: 50, sessions: 10, requests: 5 };
      this.recentUsers = data.recentUsers || [
        { id: 1, name: "Test User", email: "test@example.com", created_at: "2025-09-06" },
        { id: 2, name: "Demo User", email: "demo@example.com", created_at: "2025-09-05" },
      ];
    } catch (error) {
      console.error("Dashboard load failed:", error.response?.data || error);
      setAuthToken(null);
      this.$router.push({ name: "login" });
    } finally {
      this.loading = false;
    }
  },
  methods: {
    async logout() {
      this.loading = true;
      try {
        await axios.post("/logout");
      } catch (error) {
        console.error("Logout failed:", error.response?.data || error);
      } finally {
        setAuthToken(null);
        this.$router.push({ name: "login" });
      }
    },
  },
};
</script>

<style scoped>
#admin-dashboard aside a.nav-link.active {
  font-weight: bold;
  text-decoration: underline;
}
</style>
