// // resources/js/stores/auth.js
// import { defineStore } from "pinia";
// import api, { setAuthToken } from "@/services/api";

// export const useAuthStore = defineStore("auth", {
//   state: () => ({
//     user: null,
//     token: localStorage.getItem("auth_token") || null,
//   }),

//   getters: {
//     isAuthenticated: (state) => !!state.token,
//   },

//   actions: {
//     async login(credentials) {
//       const { data } = await api.post("/login", credentials);
//       this.token = data.token;
//       this.user = data.user;
//       setAuthToken(this.token);
//     },

//     async fetchUser() {
//       if (!this.token) return;
//       const { data } = await api.get("/me");
//       this.user = data;
//     },

//     async logout() {
//       try {
//         await api.post("/logout");
//       } catch (e) {
//         console.warn("Logout API failed, continuing anyway", e);
//       }
//       this.user = null;
//       this.token = null;
//       setAuthToken(null);
//     },
//   },
// });


import { defineStore } from "pinia";
import api, { setAuthToken } from "@/services/api";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("auth_token") || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(credentials) {
      const { data } = await api.post("/login", credentials);
      this.token = data.token;
      this.user = data.user;
      setAuthToken(this.token);
    },

    async fetchUser() {
      if (!this.token) return;
      const { data } = await api.get("/me"); // adjust endpoint for your backend
      this.user = data;
    },

    async logout() {
      try {
        await api.post("/logout");
      } catch (e) {
        console.warn("Logout API failed, continuing anyway", e);
      }
      this.user = null;
      this.token = null;
      setAuthToken(null);
    },
  },
});
