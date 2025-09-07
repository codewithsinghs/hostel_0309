<script setup>
import { ref, onMounted } from "vue";
import axios from "@/services/axios";
import Swal from "sweetalert2";

const props = defineProps({
  resourceUrl: { type: String, required: true }, // '/admin/posts'
  resourceName: { type: String, default: "Item" },
});

const emits = defineEmits(["saved", "closed"]);

const modalEl = ref(null);
const modalInstance = ref(null);

const loading = ref(false);
const isEdit = ref(false);
const serverMessage = ref("");
const serverMessageClass = ref("alert-info");

const form = ref({
  id: null,
  title: "",
  content: "",
  category: "",
  author: "",
  date: "",
  status: true, // boolean
  image: null, // File | null
  imagePreview: null, // string URL or base64
});

const errors = ref({});

// bootstrap modal
onMounted(() => {
  modalInstance.value = new bootstrap.Modal(modalEl.value);
});

// ========== OPEN MODES ==========
function openCreate() {
  isEdit.value = false;
  resetForm();
  modalInstance.value.show();
}

async function openEdit(id) {
  resetForm();
  isEdit.value = true;
  loading.value = true;
  try {
    const res = await axios.get(`${props.resourceUrl}/${id}`);
    const payload = res.data.data ?? res.data;

    form.value.id = payload.id ?? null;
    form.value.title = payload.title ?? "";
    form.value.content = payload.content ?? "";
    form.value.category = payload.category ?? "";
    form.value.author = payload.author ?? "";
    form.value.date = payload.date ? payload.date.split("T")[0] : "";
    form.value.status = payload.status === true || payload.status === 1 || payload.status === "1";
    form.value.imagePreview = payload.image_url ?? payload.image ?? null;
    form.value.image = null;

    modalInstance.value.show();
  } catch (err) {
    console.error("Failed to load resource:", err);
    Swal.fire("Error", "Failed to load record for editing.", "error");
  } finally {
    loading.value = false;
  }
}

function close() {
  modalInstance.value.hide();
  emits("closed");
}

// ========== FILE HANDLING ==========
function onFileChange(e) {
  const f = e.target.files[0];
  form.value.image = f || null;
  if (!f) {
    return;
  }
  const reader = new FileReader();
  reader.onload = (ev) => (form.value.imagePreview = ev.target.result);
  reader.readAsDataURL(f);
}

// ========== RESET ==========
function resetForm() {
  errors.value = {};
  serverMessage.value = "";
  serverMessageClass.value = "alert-info";
  form.value = {
    id: null,
    title: "",
    content: "",
    category: "",
    author: "",
    date: "",
    status: true,
    image: null,
    imagePreview: null,
  };
}

// ========== SUBMIT ==========
async function submit() {
  errors.value = {};
  serverMessage.value = "";
  loading.value = true;

  try {
    const formData = new FormData();
    Object.keys(form.value).forEach((key) => {
      if (key === "imagePreview") return; // do not send preview
      if (form.value[key] !== null) formData.append(key, form.value[key]);
    });

    let res;
    if (isEdit.value && form.value.id) {
      formData.append("_method", "PATCH"); // Laravel expects
      res = await axios.post(`${props.resourceUrl}/${form.value.id}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      Swal.fire("Updated", `${props.resourceName} updated successfully!`, "success");
    } else {
      res = await axios.post(props.resourceUrl, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      });
      Swal.fire("Created", `${props.resourceName} created successfully!`, "success");
    }

    modalInstance.value.hide();
    emits("saved", res.data);
    resetForm();
  } catch (err) {
    if (err.response && err.response.status === 422) {
      errors.value = err.response.data.errors || {};
      serverMessage.value = "Please fix the errors below.";
      serverMessageClass.value = "alert-danger";
    } else {
      console.error("Submit error:", err);
      Swal.fire("Error", "Something went wrong while saving.", "error");
    }
  } finally {
    loading.value = false;
  }
}

defineExpose({ openCreate, openEdit });
</script>
