<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Reports</h4>
      <button class="btn btn-success" @click="createReport">+ Create Report</button>
    </div>

    <div v-if="loading" class="text-center py-5">
      <span class="spinner-border spinner-border-lg"></span>
    </div>

    <table v-else class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Content</th>
          <th>Status</th>
          <th>Created</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="reports.length === 0">
          <td colspan="6" class="text-center">No reports found</td>
        </tr>
        <tr v-for="r in reports" :key="r.id">
          <td>{{ r.id }}</td>
          <td>{{ r.title }}</td>
          <td>{{ r.content }}</td>
          <td>
            <input type="checkbox" class="form-check-input" :checked="r.status" @change="toggleStatus(r, $event)" />
          </td>
          <td>{{ formatDate(r.created_at) }}</td>
          <td>
            <button class="btn btn-sm btn-info me-1" @click="openModal(r)">ViewM</button>
            <button class="btn btn-info btn-sm me-1" @click="viewReport(r)">View</button>
            <button class="btn btn-warning btn-sm me-1" @click="editReport(r)">Edit</button>
            <button class="btn btn-danger btn-sm" @click="deleteReport(r)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>



    <!-- Modal -->
    <div class="modal fade" tabindex="-1" ref="reportModal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ selectedReport?.title }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <p><strong>Content:</strong></p>
            <p>{{ selectedReport?.content }}</p>
            <p><strong>Status:</strong> {{ selectedReport?.status ? 'Active' : 'Inactive' }}</p>
            <p><strong>Created At:</strong> {{ selectedReport?.created_at }}</p>
            <p><strong>Updated At:</strong> {{ selectedReport?.updated_at }}</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closeModal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from '../../../services/axios';

export default {
  name: "ReportsIndex",
  data() {
    return {
      reports: [],
      loading: true,
      selectedReport: null, // ✅ selected report for modal
      reportModal: null,    // ✅ Bootstrap modal instance
    };
  },
  async created() {
    await this.loadReports();
  },

  mounted() {
    this.reportModal = new bootstrap.Modal(this.$refs.reportModal);
    this.loadReports();
  },
  
  methods: {
    async loadReports() {
      this.loading = true;
      try {
        const { data } = await axios.get('/admin/reports'); // ✅ match API URL
        if (data.success) {
          this.reports = data.data; // must match controller response key
        } else {
          console.error('Failed to load reports:', data.message);
        }
      } catch (err) {
        console.error('Failed to load reports:', err.response?.data || err);
      } finally {
        this.loading = false;
      }
    },

    formatDate(date) {
      return new Date(date).toLocaleString();
    },

    createReport() {
      this.$router.push({ name: 'admin.reports.create' });
    },

    viewReport(report) {
      this.$router.push({ name: 'admin.reports.view', params: { id: report.id } });
    },

    editReport(report) {
      this.$router.push({ name: 'admin.reports.edit', params: { id: report.id } });
    },

    openModal(report) {
      this.selectedReport = report;
      this.reportModal.show();
    },

    closeModal() {
      this.selectedReport = null;
      this.reportModal.hide();
    },

    async deleteReport(report) {
      if (!confirm(`Delete report "${report.title}"?`)) return;
      try {
        await axios.delete(`/admin/reports/${report.id}`);
        this.reports = this.reports.filter(r => r.id !== report.id);
      } catch (err) {
        console.error('Delete failed:', err);
        alert('Failed to delete report');
      }
    },

    async toggleStatus(report, event) {
      const newStatus = event.target.checked;
      try {
        const { data } = await axios.patch(`/admin/reports/${report.id}/toggle-status`);
        console.log(data);
        report.status = data.data.status;
      } catch (err) {
        console.error('Toggle status failed:', err);
        event.target.checked = report.status; // revert
        alert('Failed to update status');
      }
    }
  }
};
</script>
