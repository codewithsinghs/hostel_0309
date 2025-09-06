<template>
    <div class="d-flex" id="admin-dashboard">
        <!-- Sidebar -->
        <aside class="bg-dark text-white vh-100 p-3" style="width: 250px">
            <h3 class="text-center mb-4">Admin Panel</h3>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <router-link to="/admin" class="nav-link text-white"
                        >Dashboard</router-link
                    >
                </li>
                <li class="nav-item mb-2">
                    <router-link to="#" class="nav-link text-white"
                        >Users</router-link
                    >
                </li>
                <li class="nav-item mb-2">
                    <router-link to="#" class="nav-link text-white"
                        >Reports</router-link
                    >
                </li>
                <li class="nav-item mt-5">
                    <button class="btn btn-danger w-100" @click="logout">
                        Logout
                    </button>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-fill p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Dashboard</h2>
                <div>
                    <span class="me-3">Hello, {{ user?.name }}</span>
                </div>
            </div>

            <!-- Metrics -->
            <div class="row mb-4">
                <div
                    class="col-md-4"
                    v-for="(value, key) in metrics"
                    :key="key"
                >
                    <div class="card text-white mb-3" :class="metricClass(key)">
                        <div class="card-body">
                            <h5 class="card-title">{{ key | capitalize }}</h5>
                            <p class="card-text">{{ value }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="card">
                <div
                    class="card-header d-flex justify-content-between align-items-center"
                >
                    <span>Recent Users</span>
                    <button
                        class="btn btn-sm btn-primary"
                        @click="refreshUsers"
                        :disabled="loading"
                    >
                        Refresh
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="u in recentUsers" :key="u.id">
                                <td>{{ u.id }}</td>
                                <td>{{ u.name }}</td>
                                <td>{{ u.email }}</td>
                                <td>{{ formatDate(u.created_at) }}</td>

                                <!-- Status Toggle -->
                                <td>
                                    <div class="form-check form-switch">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :checked="u.active"
                                            @change="toggleStatus(u)"
                                        />
                                    </div>
                                </td>

                                <!-- Action Buttons -->
                                <td>
                                    <button
                                        class="btn btn-sm btn-info me-1"
                                        @click="viewUser(u)"
                                    >
                                        View
                                    </button>
                                    <button
                                        class="btn btn-sm btn-warning me-1"
                                        @click="editUser(u)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        class="btn btn-sm btn-danger"
                                        @click="deleteUser(u)"
                                    >
                                        Delete
                                    </button>
                                </td>
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
            metrics: {},
            recentUsers: [],
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
                const { data } = await axios.get("/ndashboard");
                this.user = data.user;
                this.metrics = data.metrics;
                this.recentUsers = data.recentUsers;
            } catch (err) {
                console.error(
                    "Dashboard load failed:",
                    err.response?.data || err
                );
                setAuthToken(null);
                this.$router.push({ name: "login" });
            } finally {
                this.loading = false;
            }
        },

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

        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },

        // --------------- New Professional Methods ---------------

        // Toggle user active status
        async toggleStatus(user) {
            const newStatus = !user.active;
            try {
                const { data } = await axios.patch(
                    `/users/${user.id}/toggle-status`,
                    { active: newStatus }
                );
                user.active = data.user.active; // update locally
            } catch (error) {
                console.error(
                    "Status toggle failed:",
                    error.response?.data || error
                );
            }
        },

        // View user details
        viewUser(user) {
            this.$router.push({ name: "user.view", params: { id: user.id } });
        },

        // Edit user
        editUser(user) {
            this.$router.push({ name: "user.edit", params: { id: user.id } });
        },

        // Delete user
        async deleteUser(user) {
            if (!confirm(`Are you sure you want to delete ${user.name}?`))
                return;
            try {
                await axios.delete(`/users/${user.id}`);
                this.recentUsers = this.recentUsers.filter(
                    (u) => u.id !== user.id
                );
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
