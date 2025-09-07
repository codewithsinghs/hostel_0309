<template>
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>{{ title }}</span>
      <div>
        <button class="btn btn-sm btn-success me-2" @click="$emit('create')">
          + Create User
        </button>
        <button class="btn btn-sm btn-primary" @click="$emit('refresh')" :disabled="loading">
          <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
          Refresh
        </button>
      </div>
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
          <tr v-if="!users.length">
            <td colspan="6" class="text-center py-3">No users found</td>
          </tr>

          <tr v-for="u in users" :key="u.id">
            <td>{{ u.id }}</td>
            <td>{{ u.name }}</td>
            <td>{{ u.email }}</td>
            <td>{{ formatDate(u.created_at) }}</td>

            <!-- Status Toggle -->
            <!-- <td>
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="u.status"
                    @change="$emit('toggle-status', u)"
                  />
                </div>
              </td> -->
            <!-- Status Toggle -->
            <td>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" :checked="u.status"
                  @change="$emit('toggle-status', u, $event)" />
              </div>
            </td>



            <!-- Actions -->
            <td>
              <button class="btn btn-sm btn-info me-1" @click="$emit('view', u)">
                View
              </button>
              <button class="btn btn-sm btn-warning me-1" @click="$emit('edit', u)">
                Edit
              </button>
              <button v-if="u.id !== currentUserId" class="btn btn-sm btn-danger" @click="$emit('delete', u)">
                Delete
              </button>
              <span v-else class="badge bg-secondary" title="You cannot delete yourself">
                Self
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: "UserTable",
  props: {
    title: { type: String, default: "Users" },
    users: { type: Array, required: true },
    loading: { type: Boolean, default: false },
    currentUserId: { type: Number, required: true },
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
  },
};

</script>