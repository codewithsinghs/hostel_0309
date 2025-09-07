<script setup>
import { onMounted, ref } from "vue";
import axios from "../../../services/axios";
import Swal from "sweetalert2";
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
// import "datatables.net-buttons-bs5";

import $ from "jquery";

// attach jQuery globally so DataTables finds it
window.$ = window.jQuery = $;

// Import DataTables + extensions AFTER jQuery is set
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

// refs
const form = ref({
  id: null,
  title: "",
  slug: "",
  content: "",
  status: 1,
});
const errors = ref({});
let table = null;

// Reset form
const resetForm = () => {
  form.value = { id: null, title: "", slug: "", content: "", status: 1 };
  errors.value = {};
};

// Open modal
const openModal = (blog = null) => {
  resetForm();
  if (blog) form.value = { ...blog };
  const modal = new bootstrap.Modal("#blogModal");
  modal.show();
};

// Save blog (create/update)
const saveBlog = async () => {
  errors.value = {};
  try {
    let res;
    if (form.value.id) {
      res = await axios.put(`/admin/blogs/${form.value.id}`, form.value);
    } else {
      res = await axios.post(`/admin/blogs`, form.value);
    }
    Swal.fire("Success", res.data.message || "Saved successfully!", "success");
    bootstrap.Modal.getInstance(document.getElementById("blogModal")).hide();
    table.ajax.reload();
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors || {};
    } else {
      Swal.fire("Error", "Something went wrong!", "error");
    }
  }
};

// Delete blog
const deleteBlog = async (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "This will permanently delete the record.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const res = await axios.delete(`/admin/blogs/${id}`);
        Swal.fire("Deleted!", res.data.message || "Record deleted.", "success");
        table.ajax.reload();
      } catch (e) {
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
  } catch (e) {
    Swal.fire("Error", "Failed to update status.", "error");
    table.ajax.reload();
  }
};

// Initialize DataTable
onMounted(() => {
  table = $("#blogsTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: (data, callback) => {
      axios
        .get("/admin/blogs", { params: data })
        .then((res) => callback(res.data))
        .catch(() => {
          callback({
            draw: data.draw,
            recordsTotal: 0,
            recordsFiltered: 0,
            data: [],
          });
        });
    },
    columns: [
      { data: "id" },
      { data: "title" },
      { data: "slug" },
      {
        data: "status",
        render: (data, type, row) => {
          return `
            <div class="form-check form-switch">
              <input type="checkbox" class="form-check-input toggle-status"
                     data-id="${row.id}" ${data ? "checked" : ""}>
            </div>`;
        },
      },
      {
        data: null,
        orderable: false,
        render: (data, type, row) => {
          return `
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>`;
        },
      },
    ],
    drawCallback: () => {
      // Edit buttons
      $(".edit-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        openModal(rowData);
      });
      // Delete buttons
      $(".delete-btn").off().on("click", function () {
        const id = $(this).data("id");
        deleteBlog(id);
      });
      // Toggle switches
      $(".toggle-status").off().on("change", function () {
        const id = $(this).data("id");
        const status = $(this).is(":checked") ? 1 : 0;
        toggleStatus(id, status);
      });
    },
    dom: "Bfrtip",
    buttons: ["copy", "excel", "csv", "pdf", "print"],
  });
});
</script>

<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>Blogs</h2>
      <button class="btn btn-success" @click="openModal()">Add Blog</button>
    </div>

    <table id="blogsTable" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Slug</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="blogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ form.id ? "Edit Blog" : "Add Blog" }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div v-if="Object.keys(errors).length" class="alert alert-danger">
            <ul class="mb-0">
              <li v-for="(msg, key) in errors" :key="key">{{ msg[0] }}</li>
            </ul>
          </div>

          <form @submit.prevent="saveBlog">
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input v-model="form.title" type="text" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Slug</label>
              <input v-model="form.slug" type="text" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Content</label>
              <textarea v-model="form.content" rows="4" class="form-control"></textarea>
            </div>

            <div class="form-check form-switch mb-3">
              <input
                v-model="form.status"
                class="form-check-input"
                type="checkbox"
                :checked="form.status"
                true-value="1"
                false-value="0"
              />
              <label class="form-check-label">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
