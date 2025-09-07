import axios from "axios";

// Base API URL
axios.defaults.baseURL = "/api";
axios.defaults.headers.common["Accept"] = "application/json";

// Restore token on page reload
const token = localStorage.getItem("auth_token");
if (token) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

// Helper to set token dynamically
export function setAuthToken(token) {
  if (token) {
    localStorage.setItem("auth_token", token);
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
  } else {
    localStorage.removeItem("auth_token");
    delete axios.defaults.headers.common["Authorization"];
  }
}

export default axios;
