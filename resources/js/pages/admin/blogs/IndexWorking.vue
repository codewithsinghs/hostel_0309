<script setup>
import { onMounted, ref } from "vue";
import axios from "../../../services/axios";
import Swal from "sweetalert2";
import $ from "jquery";

// Make sure DataTables finds jQuery
window.$ = window.jQuery = $;

// Import DataTables with Bootstrap 5 & extensions
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

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

const openModal = (blog = null) => {
  resetForm();
  if (blog) form.value = { ...blog, image: null }; // image re-upload required
  const modal = new bootstrap.Modal("#blogModal");
  modal.show();
};

const saveBlog = async () => {
  errors.value = {};
  try {
    const formData = new FormData();
    Object.entries(form.value).forEach(([key, val]) => {
      if (val !== null) formData.append(key, val);
    });

    let res;
    if (form.value.id) {
      res = await axios.post(`/admin/blogs/${form.value.id}?_method=PUT`, formData);
    } else {
      res = await axios.post(`/admin/blogs`, formData);
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

const deleteBlog = async (id) => {
  Swal.fire({
    title: "Are you sure?",
    text: "This will permanently delete the record.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
    customClass: {
      confirmButton: "btn btn-danger me-2",
      cancelButton: "btn btn-secondary",
    },
    buttonsStyling: false,
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const res = await axios.delete(`/admin/blogs/${id}`);
        Swal.fire("Deleted!", res.data.message || "Record deleted.", "success");
        table.ajax.reload();
      } catch {
        Swal.fire("Error", "Failed to delete.", "error");
      }
    }
  });
};

const toggleStatus = async (id, status) => {
  try {
    await axios.patch(`/admin/blogs/${id}/status`, { status });
    Swal.fire("Updated", "Status changed successfully!", "success");
  } catch {
    Swal.fire("Error", "Failed to update status.", "error");
    table.ajax.reload();
  }
};

onMounted(() => {
  table = $("#blogsTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: (data, callback) => {
      axios
        .get("/admin/blogs", { params: data })
        .then((res) => callback(res.data))
        .catch(() =>
          callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] })
        );
    },
    columns: [
      { data: "id", name: "id" },
      { data: "title", name: "title" },
      { data: "content", name: "content", render: (d) => d?.slice(0, 50) + "..." },
      { data: "category", name: "category" },
      {
        data: "image",
        name: "image",
        orderable: false,
        render: (d) => d ? `<img src="/storage/${d}" class="img-thumbnail" width="50">` : "",
      },
      { data: "author", name: "author" },
      { data: "date", name: "date" },
      {
        data: "status",
        render: (d, t, row) => `
          <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input toggle-status"
                   data-id="${row.id}" ${d ? "checked" : ""}>
          </div>`,
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (data, t, row) => `
          <div class="btn-group">
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
          </div>`,
      },
    ],
    order: [[0, "desc"]],
    dom: '<"row mb-2"<"col-md-6"B><"col-md-6"f>>rt<"row mt-2"<"col-md-6"i><"col-md-6"p>>',
    buttons: [
      {
        extend: "excel",
        className: "btn btn-success btn-sm",
        exportOptions: { columns: [1, 2, 3, 5, 6] }, // exclude image & actions
      },
      {
        extend: "csv",
        className: "btn btn-info btn-sm",
        exportOptions: { columns: [1, 2, 3, 5, 6] },
      },
      {
        extend: "pdf",
        className: "btn btn-danger btn-sm",
        exportOptions: { columns: [1, 2, 3, 5, 6] },
      },
      {
        extend: "print",
        className: "btn btn-secondary btn-sm",
        exportOptions: { columns: [1, 2, 3, 5, 6] },
      },
    ],
    drawCallback: () => {
      $(".edit-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        openModal(rowData);
      });
      $(".delete-btn").off().on("click", function () {
        deleteBlog($(this).data("id"));
      });
      $(".toggle-status").off().on("change", function () {
        toggleStatus($(this).data("id"), $(this).is(":checked") ? 1 : 0);
      });
    },
  });
});
</script>
<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Manage Blogs</h3>
      <button class="btn btn-success" @click="openModal()">+ Add Blog</button>
    </div>

    <div class="table-responsive">
      <table id="blogsTable" class="table table-striped table-bordered nowrap w-100">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Category</th>
            <th>Image</th>
            <th>Author</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>

    <!-- Blog Modal -->
    <div class="modal fade" id="blogModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Blog</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveBlog">
              <div class="mb-3">
                <label class="form-label">Title</label>
                <input v-model="form.title" type="text" class="form-control" />
                <div v-if="errors.title" class="text-danger small">{{ errors.title[0] }}</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea v-model="form.content" rows="3" class="form-control"></textarea>
                <div v-if="errors.content" class="text-danger small">{{ errors.content[0] }}</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Category</label>
                <input v-model="form.category" type="text" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Author</label>
                <input v-model="form.author" type="text" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Date</label>
                <input v-model="form.date" type="date" class="form-control" />
              </div>
              <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" @change="e => form.image = e.target.files[0]" />
              </div>
              <div class="form-check form-switch mb-3">
                <input v-model="form.status" type="checkbox" class="form-check-input" :true-value="1" :false-value="0" />
                <label class="form-check-label">Active</label>
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
