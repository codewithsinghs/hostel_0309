<template>
  <div class="container mt-5">
    <h2>Dashboard</h2>

    <div v-if="loading">Loading...</div>

    <div v-else>
      <p>Welcome, {{ user.name }}</p>
      <button class="btn btn-danger" @click="logout">Logout</button>
    </div>
  </div>
</template>

<script>
import api from "../../services/api";

export default {
  name: "Dashboard",
  data() {
    return {
      user: null,
      loading: true,
    };
  },
  async created() {
    await this.loadDashboard();
  },
  methods: {
    async loadDashboard() {
      this.loading = true;
      try {
        const { data } = await api.get("/ndashboard");
        this.user = data.user;
      } catch (err) {
        console.error("Dashboard load failed:", err.response?.data || err);
        localStorage.removeItem("auth_token");
        this.$router.push({ name: "login" });
      } finally {
        this.loading = false;
      }
    },
    async logout() {
      try {
        await api.post("/logout");
      } catch (err) {
        console.error("Logout failed:", err.response?.data || err);
      } finally {
        localStorage.removeItem("auth_token");
        this.$router.push({ name: "login" });
      }
    },
  },
};
</script>
