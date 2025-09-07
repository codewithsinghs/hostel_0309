<template>
  <div>
    <h4>Edit Report</h4>

    <form v-if="form" @submit.prevent="submitForm">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" v-model="form.title" class="form-control" />
        <small class="text-danger">{{ errors.title }}</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea v-model="form.content" class="form-control" rows="4"></textarea>
        <small class="text-danger">{{ errors.content }}</small>
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" v-model="form.status" />
        <label class="form-check-label">Active</label>
      </div>

      <button class="btn btn-success" :disabled="loading">
        <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
        Update
      </button>
      <button class="btn btn-secondary ms-2" @click.prevent="cancel">Cancel</button>
    </form>

    <div v-else class="text-center py-5">
      <span class="spinner-border spinner-border-lg"></span>
    </div>
  </div>
</template>

<script>
import axios from '../../../services/axios';

export default {
  name: "ReportsEdit",
  props: ['id'],
  data() {
    return {
      form: null,
      errors: {},
      loading: false
    };
  },
  async created() {
    await this.loadReport();
  },
  methods: {
    async loadReport() {
      try {
        const { data } = await axios.get(`/admin/reports/${this.id}`);
        // console.log(data);
        this.form = { ...data.data };
      } catch (err) {
        console.error('Failed to load report:', err);
        alert('Failed to load report');
        this.$router.push({ name: 'admin.reports.index' });
      }
    },
    async submitForm() {
      this.loading = true;
      this.errors = {};
      try {
        const { data } = await axios.put(`/admin/reports/${this.id}`, this.form);
        // alert(data.message);
        this.$router.push({ name: 'admin.reports.index' });
      } catch (err) {
        if (err.response && err.response.status === 422) {
          this.errors = err.response.data.errors || {};
        } else {
          alert('Failed to update report');
          console.error(err);
        }
      } finally {
        this.loading = false;
      }
    },
    cancel() {
      this.$router.push({ name: 'admin.reports.index' });
    }
  }
};
</script>
