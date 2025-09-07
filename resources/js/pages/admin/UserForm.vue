<template>
    <div class="card">
      <div class="card-header">
        <h5>{{ isEdit ? "Edit User" : "Create User" }}</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="submitForm">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input v-model="form.name" type="text" class="form-control" required />
          </div>
  
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input v-model="form.email" type="email" class="form-control" required />
          </div>
  
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input
              v-model="form.password"
              type="password"
              class="form-control"
              :required="!isEdit"
              placeholder="Leave blank to keep current password"
            />
          </div>
  
          <div class="form-check form-switch mb-3">
            <input v-model="form.status" type="checkbox" class="form-check-input" />
            <label class="form-check-label">Active</label>
          </div>
  
          <button class="btn btn-primary" :disabled="loading">
            {{ isEdit ? "Update" : "Create" }}
          </button>
          <router-link class="btn btn-secondary ms-2" :to="{ name: 'admin' }">
            Cancel
          </router-link>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "../../services/axios";
  
  export default {
    name: "UserForm",
    props: ["id"],
    data() {
      return {
        form: {
          name: "",
          email: "",
          password: "",
          status: true,
        },
        isEdit: false,
        loading: false,
      };
    },
    async created() {
      if (this.id) {
        this.isEdit = true;
        const { data } = await axios.get(`/users/${this.id}`);
        this.form = {
          ...data.user,
          password: "",
        };
      }
    },
    methods: {
      async submitForm() {
        this.loading = true;
        try {
          if (this.isEdit) {
            await axios.put(`/users/${this.id}`, this.form);
          } else {
            await axios.post("/users", this.form);
          }
          this.$router.push({ name: "admin" });
        } catch (error) {
          console.error("Save failed:", error.response?.data || error);
        } finally {
          this.loading = false;
        }
      },
    },
  };
  </script>
  