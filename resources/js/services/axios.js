// src/services/api.js
import axios from "axios";

// Create axios instance
const api = axios.create({
  baseURL: "/api",
  headers: {
    Accept: "application/json",
  },
});

// Token utilities
const TOKEN_KEY = "auth_token";

export function getAuthToken() {
  return localStorage.getItem(TOKEN_KEY);
}

export function setAuthToken(token) {
  if (token) {
    localStorage.setItem(TOKEN_KEY, token);
    api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  } else {
    localStorage.removeItem(TOKEN_KEY);
    delete api.defaults.headers.common["Authorization"];
  }
}

// Attach token on load
const existingToken = getAuthToken();
if (existingToken) {
  setAuthToken(existingToken);
}

// Request Interceptor
api.interceptors.request.use(
  (config) => {
    // You could attach other headers like X-CSRF-Token here if needed
    return config;
  },
  (error) => Promise.reject(error)
);

// Response Interceptor
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Handle unauthorized errors globally
    if (error.response?.status === 401) {
      setAuthToken(null); // clear token
      window.location.href = "/login"; // redirect to login
    }

    // Optionally handle other status codes like 403, 500, etc.
    return Promise.reject(error);
  }
);

export default api;
