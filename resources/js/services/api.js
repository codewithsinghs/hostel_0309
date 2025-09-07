// // resources/js/services/api.js
// import axios from "axios";

// // Axios instance
// const api = axios.create({
//   baseURL: import.meta.env.VITE_API_BASE_URL || "/api",
//   headers: {
//     "Content-Type": "application/json",
//   },
// });

// // Token helper
// export function setAuthToken(token) {
//   if (token) {
//     localStorage.setItem("auth_token", token);
//     api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
//   } else {
//     localStorage.removeItem("auth_token");
//     delete api.defaults.headers.common["Authorization"];
//   }
// }

// // Attach token on requests
// api.interceptors.request.use((config) => {
//   const token = localStorage.getItem("auth_token");
//   if (token) {
//     config.headers.Authorization = `Bearer ${token}`;
//   }
//   return config;
// });

// // Auto-logout on 401
// api.interceptors.response.use(
//   (response) => response,
//   (error) => {
//     if (error.response?.status === 401) {
//       setAuthToken(null);
//       window.location.href = "/login";
//     }
//     return Promise.reject(error);
//   }
// );

// export default api;


import axios from "axios";

// Create axios instance
const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "/api",
  headers: { "Content-Type": "application/json" },
  withCredentials: true,
});

// Token handling
export function setAuthToken(token) {
  if (token) {
    localStorage.setItem("auth_token", token);
    api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  } else {
    localStorage.removeItem("auth_token");
    delete api.defaults.headers.common["Authorization"];
  }
}

// Request interceptor
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("auth_token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// Response interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      setAuthToken(null);
      window.location.href = "/login";
    }
    return Promise.reject(error);
  }
);

// Reusable toggleStatus helper
export const toggleStatus = async (resource, id) => {
  return api.patch(`/admin/${resource}/${id}/status`);
};

export default api;
