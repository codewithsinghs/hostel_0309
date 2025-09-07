<script setup>
import { onMounted, ref } from "vue";
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

const emit = defineEmits(["create", "custom-action"]);


const props = defineProps({
  resourceUrl: { type: String, required: true },
  resourceName: { type: String, required: true },
  columns: { type: Array, required: true }, 
  exportButtons: { type: Array, default: () => ["csv", "excel", "pdf", "print"] }
});

let table = null;
const selectedRow = ref({});
const isEdit = ref(false);

const initTable = () => {
  table = $("#resourceTable").DataTable({
    processing: true,
    serverSide: true,
    responsive: {
    details: { type: "column", target: 0 } // first column becomes expand control
  },
  columnDefs: [
    { className: "dtr-control", orderable: false, targets: 0 } // responsive control
  ],
    ajax: (data, callback) => {
      axios
        .get(props.resourceUrl, { params: data })
        .then((res) => callback(res.data))
        .catch(() =>
          callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] })
        );
    },
    responsive: true,
    columns: props.columns.map((col) => {
      return {
        data: col.data ?? null,
        name: col.name ?? col.data,
        orderable: col.sortable !== false,
        searchable: col.searchable !== false,
        render: (data, type, row) => {
          if (col.render) {
            return col.render(data, row);
          }
          return data ?? "—"; // handle null gracefully
        },
      };
    }),
    dom:
      "<'row mb-2'<'col-sm-12 col-md-4 'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4 'f>>" +
      "<'row'<'col-sm-12 'tr>>" +
      "<'row mt-2 '<'col-sm-12 col-md-5 'i><'col-sm-12 col-md-7 'p>>",
    lengthMenu: [10, 25, 50, 100],
    buttons: props.exportButtons.map((btn) => ({
      extend: btn,
      className: "btn btn-sm btn-outline-secondary",
    })),
    
    drawCallback: () => {
      // Handle dynamic buttons
      $(".action-btn").off().on("click", function () {
        const id = $(this).data("id");
        const action = $(this).data("action");
        const rowData = table.row($(this).parents("tr")).data();
        handleAction(action, rowData);
      });
    },
  });
};

// Generic action handler
const handleAction = (action, row) => {
  if (action === "view") {
    selectedRow.value = row;
    isEdit.value = false;
    $("#resourceModal").modal("show");
  } else if (action === "edit") {
    selectedRow.value = { ...row };
    isEdit.value = true;
    $("#resourceModal").modal("show");
  } else if (action === "delete") {
    Swal.fire({
      title: `Delete ${props.resourceName}?`,
      text: row.title ?? row.name ?? "This record",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete",
    }).then((result) => {
      if (result.isConfirmed) {
        axios.delete(`${props.resourceUrl}/${row.id}`).then(() => {
          Swal.fire("Deleted!", `${props.resourceName} has been deleted.`, "success");
          table.ajax.reload();
        });
      }
    });
  } else {
    // custom action handler
    emit("custom-action", { action, row });
  }
};

onMounted(initTable);
</script>

<template>
  <div class="card shadow-sm mx-auto" style="max-width: 1200px;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">{{ resourceName }}</h5>
      <button class="btn btn-success btn-sm" @click="$emit('create')">
        <i class="bi bi-plus-circle"></i> Add New
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="resourceTable"
          class="table table-striped table-bordered table-hover align-middle nowrap"
          style="width: 100%"
        >
          <thead class="table-light">
            <tr>
              <th v-for="col in columns" :key="col.data">{{ col.title }}</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <!-- View/Edit Modal -->
  <div class="modal fade" id="resourceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ isEdit ? `Edit ${resourceName}` : `${resourceName} Details` }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <pre>{{ selectedRow }}</pre>
          <!-- You can replace <pre> with ResourceFormModal later -->
        </div>
      </div>
    </div>
  </div>
</template>
