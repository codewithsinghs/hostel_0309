<template>
  <div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4 text-center">Login</h2>
    <div v-if="serverError" class="alert alert-danger">{{ serverError }}</div>

    <form @submit.prevent="handleLogin" novalidate>
      <div class="mb-3">
        <label>Email</label>
        <input
          v-model="form.email"
          type="email"
          class="form-control"
          :class="{ 'is-invalid': errors.email }"
        />
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
      </div>

      <div class="mb-3">
        <label>Password</label>
        <input
          v-model="form.password"
          type="password"
          class="form-control"
          :class="{ 'is-invalid': errors.password }"
        />
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
      </div>

      <button type="submit" class="btn btn-primary w-100" :disabled="loading">
        {{ loading ? "Logging in..." : "Login" }}
      </button>
    </form>
  </div>
</template>

<script>
import axios, { setAuthToken } from "../../services/axios";

export default {
  name: "Login",
  data() {
    return {
      form: { email: "", password: "" },
      errors: {},
      serverError: null,
      loading: false,
    };
  },
  methods: {
    async handleLogin() {
      this.errors = {};
      this.serverError = null;
      this.loading = true;

      try {
        const { data } = await axios.post("/login", this.form);

        if (data.token) setAuthToken(data.token);

        this.$router.push({ name: "admin.dashboard" });
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors || {};
        } else {
          this.serverError = error.response?.data?.message || "Login failed!";
        }
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
