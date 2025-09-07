// resources/js/stores/toast.js
import { defineStore } from "pinia";
import { ref } from "vue";

export const useToastStore = defineStore("toast", () => {
  const message = ref(null);
  const type = ref("success"); // success, error, warning, info
  const visible = ref(false);

  function show(msg, msgType = "success", duration = 3000) {
    message.value = msg;
    type.value = msgType;
    visible.value = true;

    setTimeout(() => {
      visible.value = false;
      message.value = null;
    }, duration);
  }

  function hide() {
    visible.value = false;
    message.value = null;
  }

  return { message, type, visible, show, hide };
});
