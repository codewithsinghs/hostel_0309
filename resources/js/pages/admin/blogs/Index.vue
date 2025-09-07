<script setup>
import { onMounted, ref } from "vue";
import axios from "../../../services/axios";
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

const table = ref(null);
const form = ref({
    id: null,
    title: "",
    content: "",
    category: "",
    author: "",
    date: "",
    status: 1,
    image: null,
    imagePreview: "", // existing or uploaded image preview
});
const errors = ref({});
const isEdit = ref(false);
const selectedResource = ref({});

const resourceUrl = "/admin/blogs";
const resourceName = "Blog";

// ----- Helper Methods -----
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
    isEdit.value = false;
};

const handleErrors = (err) => {
    errors.value = {};
    if (err.response?.data?.errors) {
        for (let key in err.response.data.errors) {
            errors.value[key] = err.response.data.errors[key];
        }
    } else if (err.response?.data?.message) {
        Swal.fire("Error", err.response.data.message, "error");
    } else {
        Swal.fire("Error", "Something went wrong", "error");
    }
};

// const onFileChange = (e) => {
//   const file = e.target.files[0];
//   form.value.image = file;
//   if (file) {
//     const reader = new FileReader();
//     reader.onload = (e) => (form.value.imagePreview = e.target.result);
//     reader.readAsDataURL(file);
//   }
// };
const onFileChange = (e) => {
    const file = e.target.files[0];
    form.value.image = file;

    if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            form.value.imagePreview = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
};



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

const resetImagePreview = () => {
    form.value.imagePreview = null;
};
const openFormModal = (resource = null) => {
    resetForm(); // reset all form fields and errors
    resetImagePreview(); // only reset image preview
    if (resource) {
        form.value = { ...resource };
        isEdit.value = true;

        // Set image preview if existing
        form.value.imagePreview = resource.image
            ? `/storage/blogs/${resource.image}` // adjust path according to your backend
            : null;

        // Ensure status is boolean for checkbox or select
        form.value.status = !!resource.status;
    } else {
        isEdit.value = false;
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

const toggleStatus = async (id, status) => {
    try {
        await axios.patch(`${resourceUrl}/${id}/status`, { status });
        Swal.fire("Updated", "Status updated successfully", "success");
        table.value.ajax.reload();
    } catch (err) {
        handleErrors(err);
        table.value.ajax.reload();
    }
};

// ----- Save (Create/Update) -----
const saveResource = async () => {
    try {
        const formData = new FormData();
        // Object.keys(form.value).forEach((key) => {
        //     if (form.value[key] !== null) formData.append(key, form.value[key]);
        // });
        Object.keys(form.value).forEach((key) => {
            if (key === 'imagePreview') return; // skip preview
            if (key === 'image' && !(form.value.image instanceof File)) return; // only append if new file
            let value = form.value[key];

            // convert boolean-like strings to 0/1
            if (key === 'status') {
                value = value === true || value === 'true' || value === 1 || value === '1' ? 1 : 0;
            }

            if (value !== null) formData.append(key, value);
        });


        let response;
        if (isEdit.value) {
            formData.append("_method", "PUT"); // important for Laravel
            response = await axios.post(`${resourceUrl}/${form.value.id}`, formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
            Swal.fire("Updated", `${resourceName} updated successfully!`, "success");
        } else {
            response = await axios.post(resourceUrl, formData, {
                headers: { "Content-Type": "multipart/form-data" },
            });
            Swal.fire("Created", `${resourceName} created successfully!`, "success");
        }

        // Close modal
        const modalEl = document.getElementById("formModal");
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        table.value.ajax.reload();
        resetForm();
    } catch (err) {
        handleErrors(err);
    }
};

// Delete
const deleteResource = (id, title) => {
    Swal.fire({
        title: "Are you sure?",
        text: `Delete ${resourceName} "${title}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete",
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`${resourceUrl}/${id}`);
                Swal.fire("Deleted!", `${resourceName} has been deleted.`, "success");
                table.value.ajax.reload();
            } catch (err) {
                handleErrors(err);
            }
        }
    });
};

// ----- Initialize DataTable -----
onMounted(() => {
    table.value = $("#resourceTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: (data, callback) => {
            axios
                .get(resourceUrl, { params: data })
                .then((res) => callback(res.data))
                .catch(() =>
                    callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] })
                );
        },
        responsive: true,
        columnDefs: [
            { className: "dtr-control", orderable: false, targets: 0 },
            { responsivePriority: 1, targets: 2 },
            { responsivePriority: 2, targets: 6 },
            { responsivePriority: 3, targets: 7 },
        ],
        columns: [
            { data: null, defaultContent: "" },
            { data: "id", name: "id" },
            { data: "title", name: "title" },
            { data: "category", name: "category" },
            { data: "author", name: "author" },
            { data: "date", name: "date" },
            {
                data: "status",
                render: (data, type, row) =>
                    `<div class="form-check form-switch">
            <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${data ? "checked" : ""
                    }>
          </div>`,
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row) => `
          <div class="btn-group" role="group">
            <button class="btn btn-sm btn-info view-btn" data-id="${row.id}"><i class="bi bi-eye text-white"></i></button>
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}"><i class="bi bi-pencil text-white"></i></button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}"><i class="bi bi-trash text-white"></i></button>
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
                openFormModal(rowData);
            });

            $(".delete-btn").off().on("click", function () {
                const rowData = table.value.row($(this).parents("tr")).data();
                deleteResource(rowData.id, rowData.title);
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
                <h5 class="mb-0">{{ resourceName }}s</h5>
                <button class="btn btn-success btn-sm" @click="openFormModal()">
                    <i class="bi bi-plus-circle"></i> Add New
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="resourceTable" class="table table-striped table-bordered table-hover align-middle nowrap"
                        style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th></th>
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
        </div>

        <!-- Add/Edit Modal -->
        <div class="modal fade" id="formModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ isEdit ? 'Edit ' + resourceName : 'Add ' + resourceName }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveResource">
                            <div class="mb-3">
                                <label>Title</label>
                                <input v-model="form.title" type="text" class="form-control"
                                    :class="{ 'is-invalid': errors.title }">
                                <div v-if="errors.title" class="invalid-feedback">{{ errors.title[0] }}</div>
                            </div>
                            <div class="mb-3">
                                <label>Content</label>
                                <textarea v-model="form.content" class="form-control"
                                    :class="{ 'is-invalid': errors.content }"></textarea>
                                <div v-if="errors.content" class="invalid-feedback">{{ errors.content[0] }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Category</label>
                                    <input v-model="form.category" type="text" class="form-control"
                                        :class="{ 'is-invalid': errors.category }">
                                    <div v-if="errors.category" class="invalid-feedback">{{ errors.category[0] }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Author</label>
                                    <input v-model="form.author" type="text" class="form-control"
                                        :class="{ 'is-invalid': errors.author }">
                                    <div v-if="errors.author" class="invalid-feedback">{{ errors.author[0] }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Date</label>
                                    <input v-model="form.date" type="date" class="form-control"
                                        :class="{ 'is-invalid': errors.date }">
                                    <div v-if="errors.date" class="invalid-feedback">{{ errors.date[0] }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Status</label>
                                    <!-- <select v-model.number="form.status" class="form-select" :class="{'is-invalid': errors.status}">
                    <option :value="1">Active</option>
                    <option :value="0">Inactive</option>
                  </select> -->
                                    <select v-model="form.status" class="form-select">
                                        <option :value="true">Active</option>
                                        <option :value="false">Inactive</option>
                                    </select>


                                    <div v-if="errors.status" class="invalid-feedback">{{ errors.status[0] }}</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Image</label>
                                <!-- @change="e => form.image = e.target.files[0]" -->
                                <!-- <input type="file" @change="onFileChange" class="form-control" :class="{'is-invalid': errors.image}">
                <div v-if="errors.image" class="invalid-feedback">{{ errors.image[0] }}</div>
              </div>
              <div v-if="form.imagePreview">
                <img :src="form.imagePreview" class="img-thumbnail" style="max-height:200px">
                </div> -->
                                <input type="file" @change="onFileChange" class="form-control"
                                    :class="{ 'is-invalid': errors.image }">
                                <div v-if="errors.image" class="invalid-feedback">{{ errors.image[0] }}</div>
                            </div>

                            <div v-if="form.imagePreview">
                                <img :src="form.imagePreview" class="img-thumbnail"
                                    style="max-height:100px; width: auto;">
                            </div>
                            <button type="submit" class="btn btn-primary">{{ isEdit ? 'Update' : 'Create' }}</button>
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
                        <p><strong>Category:</strong> {{ selectedResource.category }}</p>
                        <p><strong>Author:</strong> {{ selectedResource.author }}</p>
                        <p><strong>Date:</strong> {{ selectedResource.date }}</p>
                        <p><strong>Status:</strong>
                            <span :class="['badge', selectedResource.status ? 'bg-success' : 'bg-secondary']">
                                {{ selectedResource.status ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                        <div v-if="selectedResource.image">
                            <img :src="selectedResource.image" class="img-fluid"
                                style="max-height:200px; object-fit:cover;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
