<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();
const form = ref({ name: "", email: "", password: "" });

const submit = async () => {
  try {
    await axios.post("/users/store", form.value);
    router.push({ name: "admin.users.index" });
  } catch (e) {
    console.error(e.response?.data);
  }
};
</script>

<template>
  <div class="container mt-4">
    <h3>Create User</h3>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label>Name</label>
        <input v-model="form.name" class="form-control" />
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input v-model="form.email" class="form-control" />
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" v-model="form.password" class="form-control" />
      </div>
      <button class="btn btn-primary">Save</button>
    </form>
  </div>
</template>
