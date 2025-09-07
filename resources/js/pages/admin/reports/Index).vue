<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Reports</h4>
      <button class="btn btn-success" @click="createReport">+ Create Report</button>
    </div>

    <div v-if="loading" class="text-center py-5">
      <span class="spinner-border spinner-border-lg"></span>
    </div>
    <!-- Reports Index Table -->
    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in reports" :key="report.id">
          <td>{{ report.id }}</td>
          <td>{{ report.title }}</td>
          <td>
            <input type="checkbox" :checked="report.status" @change="toggleStatus(report, $event)">
          </td>
          <td>
            <button class="btn btn-sm btn-info me-1" @click="openModal(report)">
              View
            </button>
            <button class="btn btn-sm btn-warning me-1" @click="editReport(report)">
              Edit
            </button>
            <button class="btn btn-sm btn-danger" @click="deleteReport(report)">
              Delete
            </button>
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
  data() {
    return {
      reports: [],
      selectedReport: null,
      reportModal: null,
    };
  },
  mounted() {
    this.reportModal = new bootstrap.Modal(this.$refs.reportModal);
    this.loadReports();
  },
  methods: {
    async loadReports() {
      try {
        const { data } = await axios.get('/admin/reports');
        this.reports = data.data; // depends on your API
      } catch (err) {
        console.error('Failed to load reports', err);
      }
    },

    openModal(report) {
      this.selectedReport = report;
      this.reportModal.show();
    },

    closeModal() {
      this.selectedReport = null;
      this.reportModal.hide();
    },

    editReport(report) {
      this.$router.push({ name: 'admin.reports.edit', params: { id: report.id } });
    },

    async deleteReport(report) {
      if (!confirm('Are you sure?')) return;
      try {
        await axios.delete(`/admin/reports/${report.id}`);
        this.reports = this.reports.filter(r => r.id !== report.id);
      } catch (err) {
        console.error('Delete failed', err);
      }
    },

    async toggleStatus(report, event) {
      const newStatus = event.target.checked;
      try {
        const { data } = await axios.patch(`/admin/reports/${report.id}/toggle-status`, { status: newStatus });
        report.status = data.data.status;
      } catch (err) {
        console.error('Status toggle failed', err);
        event.target.checked = report.status; // revert
      }
    },
  },
};
</script>

