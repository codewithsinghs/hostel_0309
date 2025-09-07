<template>
    <div class="card">
      <div class="card-header">
        <h5>User Details</h5>
      </div>
      <div class="card-body">
        <p><strong>ID:</strong> {{ user.id }}</p>
        <p><strong>Name:</strong> {{ user.name }}</p>
        <p><strong>Email:</strong> {{ user.email }}</p>
        <p><strong>Status:</strong>
          <span :class="user.status ? 'text-success' : 'text-danger'">
            {{ user.status ? "Active" : "Inactive" }}
          </span>
        </p>
  
        <router-link class="btn btn-warning me-2" :to="{ name: 'user.edit', params: { id: user.id } }">Edit</router-link>
        <router-link class="btn btn-secondary" :to="{ name: 'admin' }">Back</router-link>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "../../services/axios";
  
  export default {
    name: "UserShow",
    props: ["id"],
    data() {
      return {
        user: {},
      };
    },
    async created() {
      const { data } = await axios.get(`/users/${this.id}`);
      this.user = data.user;
    },
  };
  </script>
  