<template>
    <div class="container mt-5">
        <h2>Dashboard</h2>
        <div v-if="loading">Loading...</div>
        <div v-else>
            <p>Welcome, {{ user?.name }}</p>
            <button class="btn btn-danger" @click="logout">Logout</button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Dashboard",
    data() {
        return {
            user: null,
            loading: true,
        };
    },
    async created() {
        try {
            const response = await axios.get("/dashboard");
            this.user = response.data.user;
        } catch (error) {
            this.$router.push({ name: "login" });
        } finally {
            this.loading = false;
        }
    },
    methods: {
        async logout() {
            await axios.post("/logout");
            localStorage.removeItem("auth_token");
            this.$router.push({ name: "login" });
        },
    },
};
</script>
