<!-- <script setup>
import { onMounted, ref } from "vue";
import axios from "../../../services/axios";
import Swal from "sweetalert2";
import $ from "jquery";

window.$ = window.jQuery = $;

import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";

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

const selectedBlog = ref({});
const isEdit = ref(false);

let table = null;

// Reset form
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

// Open form modal (Add/Edit)
const openFormModal = (blog = null) => {
  resetForm();
  if (blog) form.value = { ...blog };
  isEdit.value = !!blog;
  new bootstrap.Modal("#formModal").show();
};

// Open view modal
const openViewModal = (blog) => {
  selectedBlog.value = { ...blog };
  new bootstrap.Modal("#viewModal").show();
};

// Toggle status
const toggleStatus = async (id, status) => {
  try {
    await axios.patch(`/admin/blogs/${id}/status`, { status });
    Swal.fire("Updated", "Status changed successfully!", "success");
    table.ajax.reload();
  } catch {
    Swal.fire("Error", "Failed to update status.", "error");
    table.ajax.reload();
  }
};

onMounted(() => {
  table = $("#blogsTable").DataTable({
    processing: true,
    serverSide: true,
    ajax: (data, callback) => {
      axios
        .get("/admin/blogs", { params: data })
        .then((res) => callback(res.data))
        .catch(() =>
          callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] })
        );
    },
    responsive: true,
    columnDefs: [
      { className: "dtr-control", orderable: false, targets: 0 },
      { responsivePriority: 1, targets: 2 },
      { responsivePriority: 2, targets: 7 },
      { responsivePriority: 3, targets: 8 },
    ],
    columns: [
      { data: null, defaultContent: "" },
      { data: "id", name: "id" },
      { data: "title", name: "title" },
      { data: "content", name: "content" },
      { data: "category", name: "category" },
      { data: "author", name: "author" },
      { data: "date", name: "date" },
      {
        data: "status",
        render: (data, type, row) =>
          `<div class="form-check form-switch">
             <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${
            data ? "checked" : ""
          }>
           </div>`,
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (data, type, row) => `
          <div class="btn-group" role="group">
            <button class="btn btn-sm btn-info view-btn" data-id="${row.id}" title="View">
              <i class="bi bi-eye text-white"></i>
            </button>
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}" title="Edit">
              <i class="bi bi-pencil text-white"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}" title="Delete">
              <i class="bi bi-trash text-white"></i>
            </button>
          </div>`,
      },
    ],
    order: [[1, "desc"]],
    dom:
      "<'row mb-2'<'col-sm-12 col-md-4 'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4 'f>>" +
      "<'row'<'col-sm-12 'tr>>" +
      "<'row mt-2 '<'col-sm-12 col-md-5 'i><'col-sm-12 col-md-7 'p>>",
    lengthMenu: [10, 25, 50, 100],
    buttons: [
      { extend: "csv", className: "btn btn-sm btn-outline-secondary" },
      { extend: "excel", className: "btn btn-sm btn-outline-secondary" },
      { extend: "pdf", className: "btn btn-sm btn-outline-secondary" },
      { extend: "print", className: "btn btn-sm btn-outline-secondary" },
    ],
    drawCallback: () => {
      $(".view-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        openViewModal(rowData);
      });

      $(".edit-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        openFormModal(rowData);
      });

      $(".delete-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        Swal.fire({
          title: "Are you sure?",
          text: `Delete blog "${rowData.title}"?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete",
        }).then((result) => {
          if (result.isConfirmed) {
            axios.delete(`/admin/blogs/${rowData.id}`).then(() => {
              Swal.fire("Deleted!", "Blog has been deleted.", "success");
              table.ajax.reload();
            });
          }
        });
      });

      $(".toggle-status").off().on("change", function () {
        const id = $(this).data("id");
        const status = $(this).is(":checked") ? 1 : 0;
        toggleStatus(id, status);
      });
    },
  });
});
</script> -->



<script setup>
import { ref, onMounted } from "vue";
import axios from "../../../services/axios";
import Swal from "sweetalert2";
import $ from "jquery";

window.$ = window.jQuery = $;

import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";

// Refs
let table = ref(null);

// Edit form
const editForm = ref({
  id: null,
  title: "",
  content: "",
  category: "",
  author: "",
  date: "",
  status: 1,
  image: null, // new upload
});
const imagePreview = ref(null);
const errors = ref({});

// View data
const viewBlog = ref({});

// Reset edit form
const resetEditForm = () => {
  editForm.value = { id: null, title: "", content: "", category: "", author: "", date: "", status: 1, image: null };
  imagePreview.value = null;
  errors.value = {};
};

// Open View modal
const openViewModal = (blog) => {
  viewBlog.value = { ...blog };
  new bootstrap.Modal("#viewModal").show();
};

// Open Edit modal
const openEditModal = (blog) => {
  resetEditForm();
  editForm.value = { ...blog, image: null }; // reset new image
  imagePreview.value = blog.image || null;
  new bootstrap.Modal("#editModal").show();
};

// Handle image change
const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    editForm.value.image = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

// Save / Update blog
const saveBlog = async () => {
  errors.value = {};
  try {
    const formData = new FormData();
    for (let key in editForm.value) {
      if (editForm.value[key] !== null) formData.append(key, editForm.value[key]);
    }

    let res;
    if (editForm.value.id) {
      res = await axios.post(`/admin/blogs/${editForm.value.id}?_method=PUT`, formData);
    } else {
      res = await axios.post("/admin/blogs", formData);
    }

    Swal.fire("Success", res.data.message || "Saved successfully!", "success");
    bootstrap.Modal.getInstance(document.getElementById("editModal")).hide();
    table.value.ajax.reload();
  } catch (err) {
    if (err.response?.status === 422) errors.value = err.response.data.errors || {};
    else Swal.fire("Error", "Something went wrong!", "error");
  }
};

// Toggle status from table
const toggleStatus = async (id, status) => {
  try {
    await axios.patch(`/admin/blogs/${id}/status`, { status });
    Swal.fire("Updated", "Status changed successfully!", "success");
    table.value.ajax.reload();
  } catch {
    Swal.fire("Error", "Failed to update status.", "error");
  }
};

// Initialize DataTable
onMounted(() => {
  table.value = $("#blogsTable").DataTable({
    processing: true,
    serverSide: true,
    ajax: (data, callback) => {
      axios.get("/admin/blogs", { params: data }).then(res => callback(res.data))
        .catch(() => callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] }));
    },
    responsive: true,
    columnDefs: [
      { className: "dtr-control", orderable: false, targets: 0 },
      { responsivePriority: 1, targets: 2 },
      { responsivePriority: 2, targets: 7 },
      { responsivePriority: 3, targets: 8 },
    ],
    columns: [
      { data: null, defaultContent: "" },
      { data: "id", name: "id" },
      { data: "title", name: "title" },
      { data: "content", name: "content" },
      { data: "category", name: "category" },
      { data: "author", name: "author" },
      { data: "date", name: "date" },
      {
        data: "status",
        render: (data, type, row) =>
          `<div class="form-check form-switch">
             <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${data ? "checked" : ""}>
           </div>`,
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (data, type, row) => `
          <div class="btn-group" role="group">
            <button class="btn btn-sm btn-info view-btn" data-id="${row.id}" title="View">
              <i class="bi bi-eye"></i>
            </button>
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}" title="Edit">
              <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}" title="Delete">
              <i class="bi bi-trash"></i>
            </button>
          </div>`,
      },
    ],
    order: [[1, "desc"]],
    dom:
      "<'row mb-2'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    lengthMenu: [10, 25, 50, 100],
    buttons: [
      { extend: "csv", className: "btn btn-sm btn-outline-secondary" },
      { extend: "excel", className: "btn btn-sm btn-outline-secondary" },
      { extend: "pdf", className: "btn btn-sm btn-outline-secondary" },
      { extend: "print", className: "btn btn-sm btn-outline-secondary" },
    ],
    drawCallback: () => {
      $(".view-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        openViewModal(rowData);
      });
      $(".edit-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        openEditModal(rowData);
      });
      $(".delete-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        Swal.fire({
          title: "Are you sure?",
          text: `Delete blog "${rowData.title}"?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete",
        }).then(result => {
          if (result.isConfirmed) {
            axios.delete(`/admin/blogs/${rowData.id}`).then(() => {
              Swal.fire("Deleted!", "Blog has been deleted.", "success");
              table.value.ajax.reload();
            });
          }
        });
      });
      $(".toggle-status").off().on("change", function () {
        const id = $(this).data("id");
        const status = $(this).is(":checked") ? 1 : 0;
        toggleStatus(id, status);
      });
    },
  });
});
</script>


<template>
  <div class="container-fluid py-3">
    <div class="card shadow-sm mx-auto" style="max-width: 1200px;">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Blogs</h5>
        <button class="btn btn-success btn-sm">
          <i class="bi bi-plus-circle"></i> Add New
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table
            id="blogsTable"
            class="table table-striped table-bordered table-hover align-middle nowrap"
            style="width: 100%"
          >
            <thead class="table-light">
              <tr>
                <th></th>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
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
    </div>

    <!-- View/Edit Modal -->
    <!-- <div
      class="modal fade"
      id="blogModal"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEdit ? "Edit Blog" : "Blog Details" }}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div v-if="!isEdit">
              <p><strong>Title:</strong> {{ selectedBlog.title }}</p>
              <p><strong>Category:</strong> {{ selectedBlog.category }}</p>
              <p><strong>Author:</strong> {{ selectedBlog.author }}</p>
              <p><strong>Date:</strong> {{ selectedBlog.date }}</p>
              <p>
                <strong>Status:</strong>
                <span
                  :class="[
                    'badge',
                    selectedBlog.status ? 'bg-success' : 'bg-secondary',
                  ]"
                >
                  {{ selectedBlog.status ? "Active" : "Inactive" }}
                </span>
              </p>
            </div>

            <form v-else @submit.prevent="updateBlog">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input v-model="selectedBlog.title" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Category</label>
                <input v-model="selectedBlog.category" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Author</label>
                <input v-model="selectedBlog.author" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Date</label>
                <input v-model="selectedBlog.date" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select v-model="selectedBlog.status" class="form-select">
                  <option :value="1">Active</option>
                  <option :value="0">Inactive</option>
                </select>
              </div>
              <button class="btn btn-primary">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div> -->
    
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

