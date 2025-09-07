<script setup>
import ResourceTable from "@/components/ResourceTable.vue";

const postColumns = [
  { title: "ID", data: "id" },
  { title: "Title", data: "title" },
  { title: "Author", data: "author" },
  { title: "Date", data: "date" },
  { 
    title: "Status", 
    data: "status", 
    sortable: false,
    render: (data, row) => `
      <span class="badge ${data ? "bg-success" : "bg-secondary"}">
        ${data ? "Active" : "Inactive"}
      </span>`
  },
  { 
    title: "Actions", 
    data: null, 
    sortable: false,
    render: (data, row) => `
      <div class="btn-group">
        <button class="btn btn-sm btn-info action-btn" data-action="view" data-id="${row.id}">
          <i class="bi bi-eye text-white"></i>
        </button>
        <button class="btn btn-sm btn-primary action-btn" data-action="edit" data-id="${row.id}">
          <i class="bi bi-pencil text-white"></i>
        </button>
        <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-id="${row.id}">
          <i class="bi bi-trash text-white"></i>
        </button>
      </div>`
  },
];
</script>

<template>
  <ResourceTable
    resource-url="/admin/posts"
    resource-name="Post"
    :columns="postColumns"
    :export-buttons="['csv','excel','pdf']"
    @create="() => console.log('Open create modal')"
    @custom-action="(payload) => console.log('Custom action', payload)"
  />
</template>
