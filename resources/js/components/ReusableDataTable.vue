<script setup>
import { ref, onMounted } from "vue";
import axios from "../services/axios";
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

const props = defineProps({
  resourceUrl: { type: String, required: true },
  resourceName: { type: String, required: true },
  columns: { type: Array, required: true },
  formSchema: { type: Array, default: () => [] },
});

const table = ref(null);
const form = ref({});
const errors = ref({});
const isEdit = ref(false);
const selectedResource = ref({});
const fileInputs = ref({});
const filePreviews = ref({});

// Reset form
const resetForm = () => {
  form.value = {};
  errors.value = {};
  fileInputs.value = {};
  filePreviews.value = {};
  isEdit.value = false;

  props.formSchema.forEach((f) => {
    if (f.type === "checkbox" || f.type === "boolean-select") {
      form.value[f.name] = false;
    } else {
      form.value[f.name] = "";
    }
  });
};

// Handle errors from API
// const handleErrors = (err) => {
//   errors.value = {};
//   if (err.response?.data?.errors) {
//     errors.value = err.response.data.errors;
//   } else if (err.response?.data?.message) {
//     Swal.fire("Error", err.response.data.message, "error");
//   } else {
//     Swal.fire("Error", "Something went wrong", "error");
//   }
// };
function handleErrors(err) {
  console.error("API error:", err);
  if (err.response) {
    const status = err.response.status;
    const data = err.response.data;
    // Laravel validation errors: { errors: { field: [..] } }
    if (data?.errors) {
      const first = Object.values(data.errors)[0][0];
      Swal.fire("Validation error", first, "error");
      return;
    }
    if (data?.message) {
      Swal.fire("Error", data.message, "error");
      return;
    }
    Swal.fire("Server error", `HTTP ${status}`, "error");
  } else if (err.request) {
    Swal.fire("Network error", "No response from server", "error");
  } else {
    Swal.fire("Error", err.message, "error");
  }
}


// Handle file input change
const onFileChange = (e, fieldName) => {
  const file = e.target.files[0];
  if (file) {
    fileInputs.value[fieldName] = file;
    const reader = new FileReader();
    reader.onload = (ev) => (filePreviews.value[fieldName] = ev.target.result);
    reader.readAsDataURL(file);
  } else {
    delete fileInputs.value[fieldName];
    filePreviews.value[fieldName] = null;
  }
};

// Open form modal (create/edit)
const openFormModal = async (id = null) => {
  resetForm();

  if (id) {
    // Fetch full resource data for edit
    try {
      const res = await axios.get(`${props.resourceUrl}/${id}`);
      console.log(res);
      const data = res.data.data.data;
      Object.keys(data).forEach((key) => {
        if (props.formSchema.find((f) => f.name === key)) {
          // Assign to form only if field exists in schema
          form.value[key] = data[key];
        }
        // If field is a file/image
        if (typeof data[key] === "string" && data[key].startsWith("http")) {
          filePreviews.value[key] = data[key];
        }
      });
      // Keep ID for update
      form.value.id = id;
      isEdit.value = true;
    } catch (err) {
      handleErrors(err);
      return;
    }
  }

  const modalEl = document.getElementById("formModal");
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
};

// Open view modal
const openViewModal = async (id) => {
  try {
    const res = await axios.get(`${props.resourceUrl}/${id}`);
    selectedResource.value = res.data.data.data;
    const modalEl = document.getElementById("viewModal");
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  } catch (err) {
    handleErrors(err);
  }
};

// Save resource (store/update)
const saveResource = async () => {
  try {
    const formData = new FormData();
    Object.keys(form.value).forEach((key) => {
      if (key === "imagePreview") return;

      let val = form.value[key];

      // Normalize booleans
      if (["status", "active"].includes(key)) {
        val = val === true || val === "true" ? 1 : 0;
      }

      if (val instanceof File) {
        formData.append(key, val);
      } else if (val !== null && val !== undefined && val !== "") {
        formData.append(key, val);
      }
    });

    if (isEdit.value) {
      formData.append("_method", "PUT");
      await axios.post(`${props.resourceUrl}/${form.value.id}`, formData);
    } else {
      await axios.post(props.resourceUrl, formData);
    }

    Swal.fire("Success", `${props.resourceName} saved successfully`, "success");
    bootstrap.Modal.getInstance(document.getElementById("formModal")).hide();
    table.value.ajax.reload();
  } catch (err) {
    handleErrors(err);
  }
};

// Delete resource
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
  console.log(status , id )
  try {
    await axios.patch(`${props.resourceUrl}/${id}/status`, { status: !!status });
    Swal.fire("Updated", "Status updated successfully", "success");
    table.value.ajax.reload(null, false);
  } catch (err) {
    handleErrors(err);
  }
};


// $("#reusableTable").on("change", ".dt-toggle-status", function () {
//   const id = $(this).data("id");
//   const newStatus = $(this).is(":checked");
//   toggleStatus(id, newStatus);
// });



// Initialize DataTable
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
      // map through your passed props.columns
      ...props.columns.map((col) => {

        if (col.data === "status") {
          return {
            data: col.data,
            name: col.name,
            orderable: false,
            searchable: false,
            className: "text-center",
            render: (data, type, row) => {
              const checked = data == 1 ? "checked" : "";
              return `
          <div class="form-check form-switch d-flex justify-content-center">
            <input 
              class="form-check-input toggle-status" 
              type="checkbox" 
              role="switch"
              data-id="${row.id}" 
              ${checked}>
          </div>
        `;
            },
          };
        }

        // fallback to original config
        //   return {
        //     data: col.data ?? null,
        //     name: col.name ?? col.data,
        //     orderable: col.sortable !== false,
        //     searchable: col.searchable !== false,
        //     render: (data, type, row) => {
        //       if (col.render) return col.render(data, row);
        //       return data ?? "—";
        //     },
        //   };
        // }),

        return col; // keep other columns unchanged
      }),
      // Actions column
      {
        data: null,
        orderable: false,
        searchable: false,
        render: (data, type, row) => `
      <div class="btn-group">
        <button class="btn btn-sm btn-info dt-view-btn" data-id="${row.id}">
          <i class="bi bi-eye text-white"></i>
        </button>
        <button class="btn btn-sm btn-primary dt-edit-btn" data-id="${row.id}">
          <i class="bi bi-pencil text-white"></i>
        </button>
        <button class="btn btn-sm btn-danger dt-delete-btn" data-id="${row.id}">
          <i class="bi bi-trash text-white"></i>
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
      $(".dt-view-btn").off().on("click", function () {
        const id = $(this).data("id");
        openViewModal(id);
      });
      $(".dt-edit-btn").off().on("click", function () {
        const id = $(this).data("id");
        openFormModal(id);
      });
      $(".dt-delete-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        deleteResource(rowData.id, rowData.title || "");
      });
      // Toggle status
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
  <div>
    <div class="d-flex justify-content-between mb-2">
      <h5>{{ resourceName }}s</h5>
      <button class="btn btn-success btn-sm" @click="openFormModal()">
        <i class="bi bi-plus-circle"></i> Add New
      </button>
    </div>

    <table id="resourceTable" class="table table-striped table-bordered table-hover align-middle nowrap"
      style="width:100%">
      <thead class="table-light">
        <tr>
          <th v-for="col in columns" :key="col.data">{{ col.name }}</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="formModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEdit ? "Edit " + resourceName : "Add " + resourceName }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveResource">
              <div v-for="field in formSchema" :key="field.name" class="mb-3">
                <label class="form-label">{{ field.label }}</label>
                <input v-if="['text', 'email', 'number', 'date'].includes(field.type)" v-model="form[field.name]"
                  :type="field.type" class="form-control" />
                <textarea v-else-if="field.type === 'textarea'" v-model="form[field.name]"
                  class="form-control"></textarea>
                <div v-else-if="field.type === 'checkbox'" class="form-check">
                  <input type="checkbox" v-model="form[field.name]" class="form-check-input" :id="field.name" />
                  <label :for="field.name" class="form-check-label">{{ field.label }}</label>
                </div>
                <input v-else-if="field.type === 'file'" type="file" class="form-control"
                  @change="e => onFileChange(e, field.name)" />
                <img v-if="filePreviews[field.name]" :src="filePreviews[field.name]" class="img-thumbnail mt-1"
                  style="max-height:100px;" />
                <div v-if="errors[field.name]" class="text-danger small">{{ errors[field.name][0] }}</div>
              </div>
              <button type="submit" class="btn btn-primary">{{ isEdit ? "Update" : "Create" }}</button>
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
            <h5 class="modal-title">{{ resourceName }} Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div v-for="field in formSchema" :key="field.name" class="mb-2">
              <strong>{{ field.label }}:</strong>
              <span v-if="field.type === 'file' && selectedResource[field.name]"><img :src="selectedResource[field.name]"
                  class="img-thumbnail" style="max-height:100px;" /></span>
              <span v-else>{{ selectedResource[field.name] }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
