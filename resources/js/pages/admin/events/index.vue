<script setup>
import ReusableDataTable from "@/components/ReusableDataTable.vue";

const columns = [
  { data: "id", name: "ID" },
  { data: "title", name: "Title" },
  { data: "content", name: "Content" },
  { data: "category", name: "Category" },
  { data: "author", name: "Author" },
  { data: "date", name: "Date" },
  { data: "status", name: "Status" },
];

const formSchema = [
  { name: "title", label: "Title", type: "text", required: true },
  { name: "content", label: "Content", type: "textarea", required: true },
  { name: "category", label: "Category", type: "text", required: true },
  { name: "author", label: "Author", type: "text", required: true },
  { name: "date", label: "Date", type: "date", required: true },
  { name: "status", label: "Status", type: "boolean-select", required: true },
  { name: "image", label: "Image", type: "file" }
];
</script>

<template>
  <div>
    <ReusableDataTable
      resource-url="/admin/events"
      resource-name="Event"
      :columns="columns"
      :form-schema="formSchema"
    />
  </div>
</template>
