<script setup>
import ResourceTable from "@/components/ResourceTable.vue";
import ResourceFormModal from "@/components/ResourceFormModal.vue";

import { ref } from "vue";

const columns = [
  { title: "ID", data: "id" },
  { title: "Title", data: "title" },
  { title: "Author", data: "author" },
  { title: "Date", data: "date" },
  {
    title: "Status",
    data: "status",
    sortable: false,
    render: (data, type, row) => {
      return `
        <div class="form-check form-switch">
          <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${
        data ? "checked" : ""
      }>
        </div>
      `;
    },
  },
  {
    title: "Actions",
    data: null,
    sortable: false,
    render: (data, type, row) => {
      return `
        <div class="btn-group">
          <button class="btn btn-sm btn-info view-btn" data-id="${row.id}">
            <i class="bi bi-eye"></i>
          </button>
          <button class="btn btn-sm btn-primary edit-btn" data-id="${row.id}">
            <i class="bi bi-pencil"></i>
          </button>
          <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
            <i class="bi bi-trash"></i>
          </button>
        </div>
      `;
    },
  },
];

const formModal = ref(null);

function handleCreate() {
  formModal.value.openCreate();
}

function handleEdit(id) {
  formModal.value.openEdit(id);
}

function reloadTable() {
  // Refresh DataTable after save
  window.dispatchEvent(new CustomEvent("reload-resource-table"));
}
</script>

<template>
  <div class="container-fluid py-3">
    <div class="card shadow-sm mx-auto" style="max-width: 1200px;">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Posts</h5>
        <button class="btn btn-success btn-sm" @click="handleCreate">
          <i class="bi bi-plus-circle"></i> Add New
        </button>
      </div>
      <div class="card-body">
        <ResourceTable
          resource-url="/admin/posts"
          resource-name="Post"
          :columns="columns"
          @edit="handleEdit"
          @view="id => alert('View ' + id)"
          @deleted="reloadTable"
          @statusToggled="reloadTable"
        />
      </div>
    </div>
  </div>

  <!-- Reusable Form Modal -->
  <ResourceFormModal
    ref="formModal"
    resource-url="/admin/posts"
    resource-name="Post"
    @saved="reloadTable"
  />
</template>
