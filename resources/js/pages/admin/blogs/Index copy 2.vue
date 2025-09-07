<template>
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Blogs</h4>
      <button class="btn btn-success btn-sm" @click="openCreateModal">
        + Create Blog
      </button>
    </div>

    <div class="card-body">
      <table
        id="blogsTable"
        class="table table-striped table-bordered nowrap w-100"
      >
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
    </div>

    <!-- Blog Modal -->
    <div
      class="modal fade"
      id="blogModal"
      tabindex="-1"
      aria-hidden="true"
      ref="blogModal"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modalTitle }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" v-model="form.title" class="form-control" />
                <small class="text-danger">{{ errors.title }}</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea
                  v-model="form.content"
                  class="form-control"
                  rows="4"
                ></textarea>
                <small class="text-danger">{{ errors.content }}</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" v-model="form.category" class="form-control" />
              </div>

              <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" v-model="form.author" class="form-control" />
              </div>

              <div class="form-check mb-3">
                <input
                  type="checkbox"
                  class="form-check-input"
                  v-model="form.status"
                />
                <label class="form-check-label">Active</label>
              </div>

              <button class="btn btn-success" :disabled="loading">
                <span
                  v-if="loading"
                  class="spinner-border spinner-border-sm me-1"
                ></span>
                Save
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import dt from "datatables.net-bs5";
import "datatables.net-responsive-bs5";

// dt(window, $); // attach DataTable to jQuery

import "datatables.net-responsive";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css";
import axios from "../../../services/axios";
import Swal from "sweetalert2";

export default {
  name: "BlogsIndex",
  data() {
    return {
      table: null,
      modal: null,
      modalTitle: "Create Blog",
      form: {
        id: null,
        title: "",
        content: "",
        category: "",
        author: "",
        date: "",
        status: false,
      },
      errors: {},
      loading: false,
    };
  },
  mounted() {
    this.initTable();
    this.modal = new bootstrap.Modal(this.$refs.blogModal);
  },
  methods: {
    initTable() {
      const vm = this;
      // this.table = $("#blogsTable").DataTable({
      //   processing: true,
      //   serverSide: true,
      //   responsive: true,
      $(this.$refs.blogsTable).DataTable({

      responsive: true,
  paging: true,
  searching: true,
  ordering: true,


        
        ajax: {
          url: "/admin/blogs",
          type: "GET",
          error: function (xhr) {
            console.error("Table load failed:", xhr.responseText);
          },
        },
        columns: [
          { data: "id" },
          { data: "title" },
          { data: "category" },
          { data: "author" },
          { data: "date" },
          {
            data: "status",
            render: function (data, type, row) {
              return `
                <div class="form-check form-switch">
                  <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${
                data ? "checked" : ""
              }>
                </div>
              `;
            },
            orderable: false,
            searchable: false,
          },
          {
            data: null,
            render: function (data, type, row) {
              return `
                <button class="btn btn-info btn-sm view-btn" data-id="${row.id}">View</button>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
              `;
            },
            orderable: false,
            searchable: false,
          },
        ],
        drawCallback: function () {
          // Handle action buttons
          $("#blogsTable .view-btn").off().on("click", function () {
            vm.viewBlog($(this).data("id"));
          });
          $("#blogsTable .edit-btn").off().on("click", function () {
            vm.editBlog($(this).data("id"));
          });
          $("#blogsTable .delete-btn").off().on("click", function () {
            vm.deleteBlog($(this).data("id"));
          });
          $("#blogsTable .toggle-status").off().on("change", function () {
            vm.toggleStatus($(this).data("id"), this.checked);
          });
        },
      });
    },

    reloadTable() {
      if (this.table) {
        this.table.ajax.reload(null, false);
      }
    },

    async toggleStatus(id, status) {
      try {
        await axios.patch(`/admin/blogs/${id}/toggle-status`, {
          status,
        });
        Swal.fire("Success", "Status updated", "success");
      } catch (err) {
        Swal.fire("Error", "Failed to update status", "error");
      }
    },

    openCreateModal() {
      this.resetForm();
      this.modalTitle = "Create Blog";
      this.modal.show();
    },

    async viewBlog(id) {
      try {
        const { data } = await axios.get(`/admin/blogs/${id}`);
        Swal.fire({
          title: data.data.title,
          html: `<p><b>Category:</b> ${data.data.category}</p>
                 <p><b>Author:</b> ${data.data.author}</p>
                 <p><b>Date:</b> ${data.data.date}</p>
                 <p>${data.data.content}</p>`,
        });
      } catch {
        Swal.fire("Error", "Failed to load blog", "error");
      }
    },

    async editBlog(id) {
      try {
        const { data } = await axios.get(`/admin/blogs/${id}`);
        this.form = { ...data.data };
        this.modalTitle = "Edit Blog";
        this.modal.show();
      } catch {
        Swal.fire("Error", "Failed to load blog", "error");
      }
    },

    async deleteBlog(id) {
      const confirm = await Swal.fire({
        title: "Are you sure?",
        text: "This will delete the blog permanently!",
        icon: "warning",
        showCancelButton: true,
      });
      if (!confirm.isConfirmed) return;

      try {
        await axios.delete(`/admin/blogs/${id}`);
        Swal.fire("Deleted", "Blog deleted", "success");
        this.reloadTable();
      } catch {
        Swal.fire("Error", "Failed to delete blog", "error");
      }
    },

    async submitForm() {
      this.loading = true;
      this.errors = {};
      try {
        if (this.form.id) {
          await axios.put(`/admin/blogs/${this.form.id}`, this.form);
          Swal.fire("Updated", "Blog updated successfully", "success");
        } else {
          await axios.post(`/admin/blogs`, this.form);
          Swal.fire("Created", "Blog created successfully", "success");
        }
        this.modal.hide();
        this.reloadTable();
      } catch (err) {
        if (err.response?.status === 422) {
          this.errors = err.response.data.errors || {};
        } else {
          Swal.fire("Error", "Failed to save blog", "error");
        }
      } finally {
        this.loading = false;
      }
    },

    resetForm() {
      this.form = {
        id: null,
        title: "",
        content: "",
        category: "",
        author: "",
        date: "",
        status: false,
      };
      this.errors = {};
    },
  },
};
</script>
