<template>
    <div class="container mt-5">
        <h2 class="mb-4">Login</h2>

        <div v-if="serverError" class="alert alert-danger">
            {{ serverError }}
        </div>
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <form @submit.prevent="handleLogin" novalidate>
            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    class="form-control"
                    :class="{ 'is-invalid': errors.email }"
                />
                <div v-if="errors.email" class="invalid-feedback">
                    {{ errors.email }}
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    v-model="form.password"
                    type="password"
                    class="form-control"
                    :class="{ 'is-invalid': errors.password }"
                />
                <div v-if="errors.password" class="invalid-feedback">
                    {{ errors.password }}
                </div>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="btn btn-primary w-100"
                :disabled="loading"
            >
                {{ loading ? "Logging in..." : "Login" }}
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Login",
    data() {
        return {
            form: { email: "", password: "" },
            errors: {},
            serverError: null,
            successMessage: null,
            loading: false,
        };
    },
    methods: {
        async handleLogin() {
            this.errors = {};
            this.serverError = null;
            this.successMessage = null;
            this.loading = true;

            try {
                const response = await axios.post("/api/login", this.form);

                this.successMessage = "Login successful!";
                // save token if your API returns one
                if (response.data.token) {
                    localStorage.setItem("auth_token", response.data.token);
                }

                // redirect to dashboard
                this.$router.push({ name: "home" });
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    this.serverError =
                        error.response?.data?.message || "Invalid credentials!";
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
