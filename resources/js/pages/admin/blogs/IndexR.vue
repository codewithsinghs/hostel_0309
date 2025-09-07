<template>
    <div class="container-fluid py-3">
      <div class="card shadow-sm mx-auto" style="max-width: 1200px;">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Blogs</h5>
          <button class="btn btn-success btn-sm" @click="openFormModal()">
            <i class="bi bi-plus-circle"></i> Add New
          </button>
        </div>
        <div class="card-body">
          <table id="blogsTable" class="table table-striped table-hover align-middle nowrap w-100">
            <thead class="table-light">
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
      </div>
  
      <!-- Form Modal -->
      <div class="modal fade" id="formModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ form.id ? "Edit Blog" : "Add Blog" }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="saveBlog">
                <div class="mb-3">
                  <label>Title</label>
                  <input v-model="form.title" type="text" class="form-control" />
                  <small class="text-danger">{{ errors.title?.[0] }}</small>
                </div>
                <div class="mb-3">
                  <label>Content</label>
                  <textarea v-model="form.content" class="form-control"></textarea>
                  <small class="text-danger">{{ errors.content?.[0] }}</small>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Category</label>
                    <input v-model="form.category" type="text" class="form-control" />
                    <small class="text-danger">{{ errors.category?.[0] }}</small>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Author</label>
                    <input v-model="form.author" type="text" class="form-control" />
                    <small class="text-danger">{{ errors.author?.[0] }}</small>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Date</label>
                    <input v-model="form.date" type="date" class="form-control" />
                    <small class="text-danger">{{ errors.date?.[0] }}</small>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label>Status</label>
                    <select v-model="form.status" class="form-select">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <label>Image</label>
                  <input type="file" class="form-control" @change="e => form.image = e.target.files[0]" />
                  <small class="text-danger">{{ errors.image?.[0] }}</small>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">{{ form.id ? "Update" : "Save" }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  
      <!-- View Modal -->
      <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Blog Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <dl class="row">
                <dt class="col-sm-3">Title</dt><dd class="col-sm-9">{{ form.title }}</dd>
                <dt class="col-sm-3">Content</dt><dd class="col-sm-9">{{ form.content }}</dd>
                <dt class="col-sm-3">Category</dt><dd class="col-sm-9">{{ form.category }}</dd>
                <dt class="col-sm-3">Author</dt><dd class="col-sm-9">{{ form.author }}</dd>
                <dt class="col-sm-3">Date</dt><dd class="col-sm-9">{{ form.date }}</dd>
                <dt class="col-sm-3">Status</dt><dd class="col-sm-9">{{ form.status ? "Active" : "Inactive" }}</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>


<script setup>
import { onMounted, ref } from "vue";
import axios from "../../../services/axios";
import Swal from "sweetalert2";
import $ from "jquery";

// Attach jQuery globally
window.$ = window.jQuery = $;

// DataTables + extensions
// DataTables + extensions (Bootstrap 5 version)
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-buttons-bs5";

// Export buttons
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

// CSS for DataTables (Bootstrap 5 styled)
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css";


import "datatables.net-dt";
import "datatables.net-responsive-dt";
import "datatables.net-buttons-dt";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

// State
const form = ref({
  id: null,
  title: "",
  content: "",
  category: "",
  author: "",
  date: "",
  status: 1,
  image: null,
});
const errors = ref({});
let table = null;

// Reset
const resetForm = () => {
  form.value = {
    id: null,
    title: "",
    content: "",
    category: "",
    author: "",
    date: "",
    status: 1,
    image: null,
  };
  errors.value = {};
};

// Open modal (Add/Edit)
const openFormModal = (blog = null) => {
  resetForm();
  if (blog) form.value = { ...blog };
  new bootstrap.Modal("#formModal").show();
};

// Open view modal
const openViewModal = (blog) => {
  form.value = { ...blog };
  new bootstrap.Modal("#viewModal").show();
};

// Save blog
const saveBlog = async () => {
  errors.value = {};
  try {
    let res;
    const payload = new FormData();
    Object.keys(form.value).forEach((k) => {
      payload.append(k, form.value[k] ?? "");
    });

    if (form.value.id) {
      payload.append("_method", "PUT");
      res = await axios.post(`/admin/blogs/${form.value.id}`, payload);
    } else {
      res = await axios.post(`/admin/blogs`, payload);
    }

    Swal.fire("Success", res.data.message || "Saved successfully!", "success");
    bootstrap.Modal.getInstance(document.getElementById("formModal")).hide();
    table.ajax.reload();
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors || {};
    } else {
      Swal.fire("Error", "Something went wrong!", "error");
    }
  }
};

// Delete
const deleteBlog = (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "This record will be deleted permanently.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const res = await axios.delete(`/admin/blogs/${id}`);
        Swal.fire("Deleted!", res.data.message, "success");
        table.ajax.reload();
      } catch {
        Swal.fire("Error", "Failed to delete.", "error");
      }
    }
  });
};

// Toggle status
const toggleStatus = async (id, status) => {
  try {
    await axios.patch(`/admin/blogs/${id}/status`, { status });
    Swal.fire("Updated", "Status changed successfully!", "success");
  } catch {
    Swal.fire("Error", "Failed to update status.", "error");
    table.ajax.reload();
  }
};

// DataTable
onMounted(() => {
    table = $("#blogsTable").DataTable({
  processing: true,
  serverSide: true,
  responsive: {
    details: {
      display: $.fn.dataTable.Responsive.display.modal({
        header: function (row) {
          const data = row.data();
          return "Details for " + data.title;
        },
      }),
      renderer: $.fn.dataTable.Responsive.renderer.tableAll({
        tableClass: "table table-sm table-bordered",
      }),
    },
  },
  ajax: (data, cb) => {
    axios.get("/admin/blogs", { params: data })
      .then((res) => cb(res.data))
      .catch(() =>
        cb({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] })
      );
  },
  columns: [
    { data: "id", name: "id" },
    { data: "title", name: "title" },
    { data: "category", name: "category" },
    { data: "author", name: "author" },
    { data: "date", name: "date" },
    {
      data: "status",
      render: (d, t, row) => `
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${d ? "checked" : ""}>
        </div>`
    },
    {
      data: null,
      orderable: false,
      searchable: false,
      render: (d, t, row) => `
        <div class="btn-group btn-group-sm" role="group">
          <button class="btn btn-info view-btn" data-id="${row.id}" title="View"><i class="bi bi-eye"></i></button>
          <button class="btn btn-primary edit-btn" data-id="${row.id}" title="Edit"><i class="bi bi-pencil"></i></button>
          <button class="btn btn-danger delete-btn" data-id="${row.id}" title="Delete"><i class="bi bi-trash"></i></button>
        </div>`
    }
  ],
  order: [[0, "desc"]],
  dom:
    '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"B>>' +
    'rt' +
    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
  buttons: [
    { extend: "copy", className: "btn btn-sm btn-outline-secondary" },
    { extend: "csv", className: "btn btn-sm btn-outline-primary" },
    { extend: "excel", className: "btn btn-sm btn-outline-success" },
    { extend: "pdf", className: "btn btn-sm btn-outline-danger" },
    { extend: "print", className: "btn btn-sm btn-outline-dark" },
  ],
  lengthMenu: [10, 25, 50, 100],
});

});
</script>
