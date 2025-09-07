<!-- <script setup>
import ResourceTable from "@/components/ResourceDataTable.vue";

const columns = [
  { data: "id", name: "ID" },
  { data: "title", name: "Title" },
  { data: "category", name: "Category" },
  { data: "author", name: "Author" },
  { data: "date", name: "Date" },
  {
    data: "status",
    name: "Status",
    render: (data, type, row) => `
      <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input toggle-status" data-id="${row.id}" ${data ? "checked" : ""}>
      </div>
    `,
  },
];
</script>

<template>
  <ResourceTable
    resource-url="/admin/posts"
    resource-name="Post"
    :columns="columns"
  />
</template> -->


<script setup>
import ResourceTable from "@/components/ResourceDataTable.vue";

const columns = [
  { data: "id", name: "ID" },
  { data: "title", name: "Title" },
  { data: "category", name: "Category" },
  { data: "author", name: "Author" },
  { data: "date", name: "Date" },
  { data: "status", name: "Status" },
];

const formSchema = [
  { name: "title", label: "Title", type: "text" },
  { name: "content", label: "Content", type: "textarea" },
  { name: "category", label: "Category", type: "text" },
  { name: "author", label: "Author", type: "text" },
  { name: "date", label: "Date", type: "date" },
  { name: "status", label: "Active", type: "checkbox" },
  { name: "image", label: "Image", type: "file" },
];
</script>

<template>
  <ResourceTable
    resourceUrl="/admin/posts"
    resourceName="Post"
    :columns="columns"
    :formSchema="formSchema"
  />
</template>
