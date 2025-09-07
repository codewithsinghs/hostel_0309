<script setup>
import { ref, onMounted } from "vue";
import axios from "../services/axios";
import Swal from "sweetalert2";
import $ from "jquery";

window.$ = window.jQuery = $;

// DataTables imports
import "datatables.net-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";

import "datatables.net-bs5/css/dataTables.bootstrap5.css";
import "datatables.net-responsive-bs5/css/responsive.bootstrap5.css";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";

/**
 * PROPS (pass from parent/index page)
 * ---------------------------------
 * resourceUrl   => "/admin/blogs"
 * resourceName  => "Blog"
 * columns       => [
 *   { data: "id", name: "ID" },
 *   { data: "title", name: "Title" },
 *   { data: "author", name: "Author" },
 *   ...
 * ]
 */
const props = defineProps({
  resourceUrl: { type: String, required: true },
  resourceName: { type: String, required: true },
  columns: { type: Array, required: true },
});

const table = ref(null);
const form = ref({});
const errors = ref({});
const isEdit = ref(false);
const selectedResource = ref({});

// ----- Helpers -----
const resetForm = () => {
  form.value = {};
  errors.value = {};
  isEdit.value = false;
};

const handleErrors = (err) => {
  errors.value = {};
  if (err.response?.data?.errors) {
    errors.value = err.response.data.errors;
  } else if (err.response?.data?.message) {
    Swal.fire("Error", err.response.data.message, "error");
  } else {
    Swal.fire("Error", "Something went wrong", "error");
  }
};

const openFormModal = (resource = null) => {
  resetForm();
  if (resource) {
    form.value = { ...resource };
    isEdit.value = true;
  }
  const modalEl = document.getElementById("formModal");
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
};

const openViewModal = (resource) => {
  selectedResource.value = { ...resource };
  const modalEl = document.getElementById("viewModal");
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
};

const saveResource = async () => {
  try {
    const formData = new FormData();
    Object.keys(form.value).forEach((key) => {
      if (form.value[key] !== null && form.value[key] !== undefined) {
        formData.append(key, form.value[key]);
      }
    });

    if (isEdit.value) {
      formData.append("_method", "PUT");
      await axios.post(`${props.resourceUrl}/${form.value.id}`, formData);
      Swal.fire("Updated", `${props.resourceName} updated successfully`, "success");
    } else {
      await axios.post(props.resourceUrl, formData);
      Swal.fire("Created", `${props.resourceName} created successfully`, "success");
    }

    bootstrap.Modal.getInstance(document.getElementById("formModal")).hide();
    table.value.ajax.reload();
  } catch (err) {
    handleErrors(err);
  }
};

const deleteResource = (id, title) => {
  Swal.fire({
    title: "Are you sure?",
    text: `Delete ${props.resourceName} "${title}"?`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete",
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        await axios.delete(`${props.resourceUrl}/${id}`);
        Swal.fire("Deleted!", `${props.resourceName} has been deleted.`, "success");
        table.value.ajax.reload();
      } catch (err) {
        handleErrors(err);
      }
    }
  });
};

const toggleStatus = async (id, status) => {
  try {
    await axios.patch(`${props.resourceUrl}/${id}/status`, { status });
    Swal.fire("Updated", "Status updated successfully", "success");
    table.value.ajax.reload();
  } catch (err) {
    handleErrors(err);
  }
};

// ----- Initialize DataTable -----
onMounted(() => {
  table.value = $("#resourceTable").DataTable({
    processing: true,
    serverSide: true,
    ajax: (data, callback) => {
      axios
        .get(props.resourceUrl, { params: data })
        .then((res) => callback(res.data))
        .catch(() =>
          callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] })
        );
    },
    responsive: true,
    columns: [
      ...props.columns,
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (data, type, row) => `
          <div class="btn-group">
            <button class="btn btn-sm btn-info view-btn" data-id="${row.id}">
              <i class="bi bi-eye text-white"></i>
            </button>
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">
              <i class="bi bi-pencil text-white"></i>
            </button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
              <i class="bi bi-trash text-white"></i>
            </button>
            <!-- custom buttons slot -->
            <button class="btn btn-sm btn-warning custom-btn" data-id="${row.id}">
              <i class="bi bi-gear"></i>
            </button>
          </div>
        `,
      },
    ],
    order: [[0, "desc"]],
    dom:
      "<'row mb-2'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>" +
      "<'row'<'col-12'tr>>" +
      "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",
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
        openFormModal(rowData);
      });

      $(".delete-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        deleteResource(rowData.id, rowData.title || "");
      });

      $(".toggle-status").off().on("change", function () {
        const id = $(this).data("id");
        const status = $(this).is(":checked") ? 1 : 0;
        toggleStatus(id, status);
      });

      $(".custom-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        Swal.fire("Custom Action", JSON.stringify(rowData), "info");
      });
    },
  });
});
</script>

<template>
  <div class="container-fluid py-3">
    <div class="card shadow-sm mx-auto" style="max-width: 1200px;">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ resourceName }}s</h5>
        <button class="btn btn-success btn-sm" @click="openFormModal()">
          <i class="bi bi-plus-circle"></i> Add New
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table
            id="resourceTable"
            class="table table-striped table-bordered table-hover align-middle nowrap"
            style="width:100%"
          >
            <thead class="table-light">
              <tr>
                <th v-for="col in columns" :key="col.data">{{ col.name }}</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

    <!-- Modals omitted for brevity -->
  </div>
</template>
