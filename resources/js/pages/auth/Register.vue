<template>
    <div class="container mt-5">
        <h2 class="mb-4">Register</h2>

        <!-- Alerts -->
        <div v-if="serverError" class="alert alert-danger">
            {{ serverError }}
        </div>
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <form @submit.prevent="handleRegister" novalidate>
            <!-- Name -->
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="form-control"
                    :class="{ 'is-invalid': errors.name }"
                />
                <div v-if="errors.name" class="invalid-feedback">
                    {{ errors.name }}
                </div>
            </div>

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

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input
                    v-model="form.password_confirmation"
                    type="password"
                    class="form-control"
                    :class="{ 'is-invalid': errors.password_confirmation }"
                />
                <div
                    v-if="errors.password_confirmation"
                    class="invalid-feedback"
                >
                    {{ errors.password_confirmation }}
                </div>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="btn btn-primary w-100"
                :disabled="loading"
            >
                {{ loading ? "Registering..." : "Register" }}
            </button>
        </form>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Register",
    data() {
        return {
            form: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            errors: {},
            serverError: null,
            successMessage: null,
            loading: false,
        };
    },
    methods: {
        async handleRegister() {
            this.errors = {};
            this.serverError = null;
            this.successMessage = null;
            this.loading = true;

            try {
                const response = await axios.post("/api/register", this.form);
                this.successMessage =
                    "Registration successful! You can now log in.";
                this.form = {
                    name: "",
                    email: "",
                    password: "",
                    password_confirmation: "",
                };
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                } else {
                    this.serverError =
                        error.response?.data?.message ||
                        "Something went wrong!";
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
