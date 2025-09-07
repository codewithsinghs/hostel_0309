<template>
  <div>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Blogs</h3>
      <button class="btn btn-success" @click="createBlog">+ Create Blog</button>
    </div>

    <table id="blogsTable" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Category</th>
          <th>Author</th>
          <th>Date</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>

    <!-- Modal for viewing blog -->
    <div
      class="modal fade"
      id="blogModal"
      tabindex="-1"
      aria-labelledby="blogModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ selectedBlog?.title }}</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <p><strong>Category:</strong> {{ selectedBlog?.category }}</p>
            <p><strong>Author:</strong> {{ selectedBlog?.author }}</p>
            <p><strong>Date:</strong> {{ formatDate(selectedBlog?.date) }}</p>
            <p><strong>Status:</strong> <span :class="selectedBlog?.status ? 'text-success' : 'text-danger'">{{ selectedBlog?.status ? 'Active' : 'Inactive' }}</span></p>
            <hr />
            <p>{{ selectedBlog?.content }}</p>
            <div v-if="selectedBlog?.image">
              <img :src="getImageUrl(selectedBlog.image)" class="img-fluid" />
            </div>
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
import axios from "@/services/axios";
import Swal from "sweetalert2";
// import "datatables.net-bs5";
// import "datatables.net-responsive-bs5";
// You don’t need datatables.net-bs5/css if using Bootstrap CSS globally. Otherwise:

// import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
// import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';

// Index.vue
// import $ from "jquery";
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-responsive";

export default {
  name: "BlogsIndex",
  data() {
    return {
      table: null,
      selectedBlog: null,
    };
  },
  mounted() {
    this.initDataTable();
  },
  methods: {
    initDataTable() {
      this.table = $("#blogsTable").DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
          url: "/admin/blogs",
          type: "GET",
          dataSrc: (json) => {
            return json.data.map((blog) => {
              blog.actions = blog.id; // placeholder
              return blog;
            });
          },
          error: (xhr) => {
            Swal.fire("Error", "Failed to fetch blogs", "error");
          },
        },
        columns: [
          { data: "id" },
          { data: "title" },
          { data: "category" },
          { data: "author" },
          { data: "date", render: (d) => new Date(d).toLocaleDateString() },
          {
            data: "status",
            render: (status, type, row) => {
              const checked = status ? "checked" : "";
              return `<div class="form-check form-switch text-center">
                        <input type="checkbox" class="form-check-input" ${checked} data-id="${row.id}" />
                      </div>`;
            },
            orderable: false,
            searchable: false,
          },
          {
            data: "actions",
            render: (id, type, row) => {
              return `
                <button class="btn btn-sm btn-info me-1 view-btn" data-id="${row.id}">View</button>
                <button class="btn btn-sm btn-warning me-1 edit-btn" data-id="${row.id}">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
              `;
            },
            orderable: false,
            searchable: false,
          },
        ],
        drawCallback: () => {
          this.attachEventHandlers();
        },
      });
    },

    attachEventHandlers() {
      // View
      $(".view-btn").off("click").on("click", (e) => {
        const id = $(e.currentTarget).data("id");
        this.viewBlog(id);
      });

      // Edit
      $(".edit-btn").off("click").on("click", (e) => {
        const id = $(e.currentTarget).data("id");
        this.$router.push({ name: "admin.blogs.edit", params: { id } });
      });

      // Delete
      $(".delete-btn").off("click").on("click", (e) => {
        const id = $(e.currentTarget).data("id");
        this.deleteBlog(id);
      });

      // Status toggle
      $(".form-check-input").off("change").on("change", async (e) => {
        const id = $(e.currentTarget).data("id");
        const checked = e.target.checked;
        await this.toggleStatus(id, checked, e.target);
      });
    },

    async viewBlog(id) {
      try {
        const { data } = await axios.get(`/admin/blogs/${id}`);
        if (data.status === "success") {
          this.selectedBlog = data.data.blog;
          $("#blogModal").modal("show");
        }
      } catch (err) {
        Swal.fire("Error", "Failed to fetch blog details", "error");
      }
    },

    closeModal() {
      this.selectedBlog = null;
      $("#blogModal").modal("hide");
    },

    createBlog() {
      this.$router.push({ name: "admin.blogs.create" });
    },

    async deleteBlog(id) {
      const result = await Swal.fire({
        title: "Are you sure?",
        text: "This blog will be permanently deleted",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
      });

      if (result.isConfirmed) {
        try {
          const { data } = await axios.delete(`/admin/blogs/${id}`);
          if (data.status === "success") {
            Swal.fire("Deleted!", data.message, "success");
            this.table.ajax.reload();
          }
        } catch (err) {
          Swal.fire("Error", "Failed to delete blog", "error");
        }
      }
    },

    async toggleStatus(id, checked, element) {
      try {
        const { data } = await axios.patch(`/admin/blogs/${id}/toggle-status`);
        if (data.status === "success") {
          Swal.fire("Success", data.message, "success");
        } else {
          element.checked = !checked; // revert
          Swal.fire("Error", data.message, "error");
        }
      } catch (err) {
        element.checked = !checked; // revert
        Swal.fire("Error", "Failed to update status", "error");
      }
    },

    formatDate(date) {
      return date ? new Date(date).toLocaleString() : "-";
    },

    getImageUrl(file) {
      return `/storage/blogs/${file}`;
    },
  },
};
</script>

<style scoped>
.table td,
.table th {
  vertical-align: middle;
}
</style>
