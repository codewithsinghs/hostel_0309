<template>
  <div>
    <h4>Report Details</h4>

    <div v-if="report" class="card p-3">
      <p><strong>ID:</strong> {{ report.id }}</p>
      <p><strong>Title:</strong> {{ report.title }}</p>
      <p><strong>Content:</strong> {{ report.content }}</p>
      <p><strong>Status:</strong>
        <span :class="report.status ? 'text-success' : 'text-danger'">
          {{ report.status ? 'Active' : 'Inactive' }}
        </span>
      </p>
      <p><strong>Created At:</strong> {{ formatDate(report.created_at) }}</p>

      <button class="btn btn-secondary" @click="back">Back</button>
    </div>

    <div v-else class="text-center py-5">
      <span class="spinner-border spinner-border-lg"></span>
    </div>
  </div>
</template>

<script>
import axios from '../../../services/axios';

export default {
  name: "ReportsView",
  props: ['id'],
  data() {
    return {
      report: null,
    };
  },
  async created() {
    await this.loadReport();
  },
  methods: {
    async loadReport() {
      try {
        const { data } = await axios.get(`/admin/reports/${this.id}`);
        console.log( data );
        this.report = data.data;
      } catch (err) {
        console.error('Failed to load report:', err);
        alert('Failed to load report');
        this.$router.push({ name: 'admin.reports.index' });
      }
    },
    back() {
      this.$router.push({ name: 'admin.reports.index' });
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    }
  }
};
</script>
