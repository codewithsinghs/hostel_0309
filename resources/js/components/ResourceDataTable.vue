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
  formSchema: { type: Array, default: () => [] }, // 👈 schema passed from page
});

const table = ref(null);
const form = ref({});
const errors = ref({});
const isEdit = ref(false);
const selectedResource = ref({});

const fileInputs = ref({});
const filePreviews = ref({});
// ----- Helpers -----
// const resetForm = () => {
//   form.value = {};
//   errors.value = {};
//   isEdit.value = false;
// };
const resetForm = () => {
  form.value = {};
  errors.value = {};
  isEdit.value = false;

  props.formSchema.forEach((f) => {
    if (f.type === "checkbox" || f.type === "boolean-select") {
      form.value[f.name] = false; // 👈 default inactive
    } else {
      form.value[f.name] = "";
    }
  });
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

const onFileChange = (e, fieldName) => {
  const file = e.target.files[0];
  if (file) {
    fileInputs.value[fieldName] = file;            // only new files
    const reader = new FileReader();
    reader.onload = (ev) => (filePreviews.value[fieldName] = ev.target.result);
    reader.readAsDataURL(file);
  } else {
    delete fileInputs.value[fieldName];
    filePreviews.value[fieldName] = null;
  }
};

// ----- Modals -----
// const openFormModal = (resource = null) => {
//   resetForm();
//   if (resource) {
//     form.value = { ...resource };
//     isEdit.value = true;
//   }
//   const modalEl = document.getElementById("formModal");
//   const modal = new bootstrap.Modal(modalEl);
//   modal.show();
// };
const openFormModal = (resource = null) => {
  // Reset everything
  form.value = {};
  fileInputs.value = {};
  filePreviews.value = {};
  isEdit.value = false;

  if (resource) {
    Object.keys(resource).forEach((key) => {
      const val = resource[key];

      // If it looks like a file URL, use it for preview only
      if (typeof val === "string" && val.startsWith("http")) {
        filePreviews.value[key] = val;  // display only
      } else {
        form.value[key] = val;           // normal form fields
      }
    });
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

// ----- CRUD -----
// const saveResource = async () => {
//   try {
//     const formData = new FormData();
//     Object.keys(form.value).forEach((key) => {
//       if (form.value[key] !== null && form.value[key] !== undefined) {
//         formData.append(key, form.value[key]);
//       }
//     });

//     if (isEdit.value) {
//       formData.append("_method", "PUT");
//       await axios.post(`${props.resourceUrl}/${form.value.id}`, formData);
//       Swal.fire("Updated", `${props.resourceName} updated successfully`, "success");
//     } else {
//       await axios.post(props.resourceUrl, formData);
//       Swal.fire("Created", `${props.resourceName} created successfully`, "success");
//     }

//     bootstrap.Modal.getInstance(document.getElementById("formModal")).hide();
//     table.value.ajax.reload();
//   } catch (err) {
//     handleErrors(err);
//   }
// };

const saveResource = async () => {
  try {
    const formData = new FormData();
    Object.keys(form.value).forEach((key) => {
      if (key === "imagePreview") return;

      let val = form.value[key];

      // ✅ Convert booleans to 1/0 for Laravel
      if (key === "status") {
        // normalize to boolean integer for Laravel
        val = form.value[key] === true || form.value[key] === "true" ? 1 : 0;
      }

      if (val instanceof File) {
        formData.append(key, val);
      } else if (val !== null && val !== undefined && val !== "") {
        formData.append(key, val);
      }
      // 👆 if image is null/empty, we skip it completely
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
    // } catch (err) {
    //   handleErrors(err);
    // }
  } catch (error) {
    console.error("Full error:", error);

    if (error.response) {
      console.error("Error response:", error.response);
      if (error.response.data && error.response.data.errors) {
        errors.value = error.response.data.errors;
      } else if (error.response.data && error.response.data.message) {
        alert(error.response.data.message); // Laravel custom message
      } else {
        alert("Something went wrong. Check console for details.");
      }
    } else {
      alert("Network or server error.");
    }
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
    table.value.ajax.reload(null, false); // reload without resetting pagination
  } catch (err) {
    handleErrors(err);
  }
};

// ----- Init DataTable -----
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
      // bind actions AFTER each draw
      $(".dt-view-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        openViewModal(rowData);
      });

      $(".dt-edit-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        openFormModal(rowData);
      });

      $(".dt-delete-btn").off().on("click", function () {
        const rowData = table.value.row($(this).parents("tr")).data();
        deleteResource(rowData.id, rowData.title || "");
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
    <!-- <div class="modal fade" id="formModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEdit ? "Edit " + resourceName : "Add " + resourceName }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveResource">
              <div class="mb-3">
                <label>Title</label>
                <input v-model="form.title" type="text" class="form-control" />
              </div>
              <div class="mb-3">
                <label>Content</label>
                <textarea v-model="form.content" class="form-control"></textarea>
              </div>
              <button type="submit" class="btn btn-primary">
                {{ isEdit ? "Update" : "Create" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="formModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isEdit ? "Edit " + resourceName : "Add " + resourceName }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveResource">
              <div v-for="field in formSchema" :key="field.name" class="mb-3">
                <label class="form-label">{{ field.label }}</label>

                <!-- text, email, number, date -->
                <input v-if="['text', 'email', 'number', 'date'].includes(field.type)" v-model="form[field.name]"
                  :type="field.type" class="form-control" />

                <!-- textarea -->
                <textarea v-else-if="field.type === 'textarea'" v-model="form[field.name]"
                  class="form-control"></textarea>

                <!-- checkbox -->
                <div v-else-if="field.type === 'checkbox'" class="form-check">
                  <input type="checkbox" v-model="form[field.name]" true-value="true" false-value="false"
                    class="form-check-input" :id="field.name" />
                  <label :for="field.name" class="form-check-label">
                    {{ field.label }}
                  </label>
                </div>

                <!-- file -->
                <input v-else-if="field.type === 'file'" type="file" class="form-control"
                  @change="e => form[field.name] = e.target.files[0]" />

                <!-- fallback -->
                <input v-else v-model="form[field.name]" type="text" class="form-control" />

                <!-- validation errors -->
                <div v-if="errors[field.name]" class="text-danger small">
                  {{ errors[field.name][0] }}
                </div>
              </div>

              <button type="submit" class="btn btn-primary">
                {{ isEdit ? "Update" : "Create" }}
              </button>
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
            <p><strong>Title:</strong> {{ selectedResource.title }}</p>
            <p><strong>Content:</strong> {{ selectedResource.content }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
