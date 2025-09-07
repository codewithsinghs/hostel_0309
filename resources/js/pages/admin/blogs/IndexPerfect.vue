<script setup>
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

let table = null;

// Reactive states for modals
const selectedBlog = ref({});
const isEdit = ref(false);

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
      { responsivePriority: 1, targets: 2 }, // Title
      { responsivePriority: 2, targets: 6 }, // Status
      { responsivePriority: 3, targets: 7 }, // Actions
    ],
    columns: [
      { data: null, defaultContent: "" }, // control column
      { data: "id", name: "id" },
      { data: "title", name: "title" },
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
      // View blog
      $(".view-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        selectedBlog.value = rowData;
        isEdit.value = false;
        $("#blogModal").modal("show");
      });

      // Edit blog
      $(".edit-btn").off().on("click", function () {
        const rowData = table.row($(this).parents("tr")).data();
        selectedBlog.value = { ...rowData };
        isEdit.value = true;
        $("#blogModal").modal("show");
      });

      // Delete blog
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

      // Toggle status
      $(".toggle-status").off().on("change", function () {
        const id = $(this).data("id");
        const status = $(this).is(":checked") ? 1 : 0;
        axios.patch(`/admin/blogs/${id}/status`, { status }).then(() => {
          Swal.fire("Updated", "Status updated successfully", "success");
        });
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
    <div
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
    </div>
  </div>
</template>



<!-- <script setup>
import { onMounted } from "vue";
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

let table = null;

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
        responsive: {
            details: {
                type: "column",
                target: "tr", // click anywhere in row to expand
            },
        },
        // columnDefs: [
        //   {
        //     className: "dtr-control", // adds the expand icon
        //     orderable: false,
        //     targets: 0,
        //   },
        // ],
        columnDefs: [
            { className: "dtr-control", orderable: false, targets: 0 },
            { responsivePriority: 1, targets: 2 }, // Title = highest priority
            { responsivePriority: 2, targets: 6 }, // Status
            { responsivePriority: 3, targets: 7 }, // Actions
        ],
        columns: [
            { data: null, defaultContent: "" }, // control column
            { data: "id", name: "id" },
            { data: "title", name: "title" },
            { data: "category", name: "category" },
            { data: "author", name: "author" },
            { data: "date", name: "date" },
            {
                data: "status",
                render: (data) =>
                    `<span class="badge ${data ? "bg-success" : "bg-secondary"}">
             ${data ? "Active" : "Inactive"}
           </span>`,
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
            "<'row mb-2'<'col-sm-12 col-md-4 'l><'col-sm-12 col-md-4 'B><'col-sm-12 col-md-4 'f>>" +
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
                Swal.fire("Blog Details", JSON.stringify(rowData, null, 2), "info");
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
                    <table id="blogsTable" class="table table-striped table-bordered table-hover align-middle nowrap"
                        style="width:100%;">
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
    </div>
</template> -->
