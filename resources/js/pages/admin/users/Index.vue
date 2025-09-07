<template>
    <div>
      <h2>Users</h2>
      <p>This is the users index page.</p>

      <!-- User Table -->
      <UserTable title="Recent Users" :users="recentUsers" :loading="loading" :current-user-id="user?.id"
        @refresh="refreshUsers" @toggle-status="toggleStatus" @view="viewUser" @edit="editUser" @delete="deleteUser"
        @create="createUser" />

    </div>
  </template>
  
  <script>
import axios, { setAuthToken } from "../../../services/axios";
import UserTable from "../../../components/admin/UserTable.vue";

export default {
  name: "Dashboard",
  components: { UserTable },

  data() {
    return {
      user: null,
      metrics: {},
      recentUsers: [],
      loading: true,
    };
  },

  async created() {
    await this.loadDashboard();
  },

  methods: {
    /** Load dashboard data */
    async loadDashboard() {
      this.loading = true;
      try {
        const { data } = await axios.get("/ndashboard");
        this.user = data.user;
        this.metrics = data.metrics;

        // Normalize status
        this.recentUsers = data.recentUsers.map(u => ({
          ...u,
          status: !!u.status,
        }));
      } catch (err) {
        console.error("Dashboard load failed:", err.response?.data || err);
        setAuthToken(null);
        this.$router.push({ name: "login" });
      } finally {
        this.loading = false;
      }
    },

    /** Refresh only users */
    async refreshUsers() {
      await this.loadDashboard();
    },

    /** Logout */
    async logout() {
      try {
        await axios.post("/logout");
      } catch (err) {
        console.error("Logout failed:", err.response?.data || err);
      } finally {
        setAuthToken(null);
        this.$router.push({ name: "login" });
      }
    },

    /** Card colors */
    metricClass(key) {
      switch (key) {
        case "users":
          return "bg-primary";
        case "sessions":
          return "bg-success";
        case "requests":
          return "bg-warning";
        default:
          return "bg-secondary";
      }
    },

    /** Toggle status */
    async toggleStatus(user) {
      const newStatus = !user.status;
      try {
        const { data } = await axios.patch(`/users/${user.id}/toggle-status`, {
          status: newStatus,
        });
        user.status = !!data.user.status;
      } catch (error) {
        console.error("Status toggle failed:", error.response?.data || error);
        user.status = !newStatus; // rollback
      }
    },
    //     async toggleStatus(user, event) {
    //   const newStatus = event.target.checked; // ✅ actual value from checkbox
    //   try {
    //     const { data } = await axios.patch(`/users/${user.id}/toggle-status`, {
    //       status: newStatus,
    //     });
    //     user.status = data.user.status; // ✅ sync with backend
    //   } catch (error) {
    //     console.error("Status toggle failed:", error.response?.data || error);
    //     // revert toggle on error
    //     event.target.checked = user.status;
    //   }
    // },


    /** View */
    viewUser(user) {
      this.$router.push({ name: "user.view", params: { id: user.id } });
    },

    /** Edit */
    editUser(user) {
      this.$router.push({ name: "user.edit", params: { id: user.id } });
    },

    /** Create */
    createUser() {
      this.$router.push({ name: "user.create" });
    },

    /** Delete */
    async deleteUser(user) {
      if (!confirm(`Are you sure you want to delete ${user.name}?`)) return;
      try {
        await axios.delete(`/users/${user.id}`);
        this.recentUsers = this.recentUsers.filter(u => u.id !== user.id);
      } catch (error) {
        console.error("Delete failed:", error.response?.data || error);
      }
    },
  },

  filters: {
    capitalize(value) {
      if (!value) return "";
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
};
</script>