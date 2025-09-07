import api, { setAuthToken } from "./api";

export async function logout() {
  try {
    await api.post("/logout");
  } catch (err) {
    console.warn("Logout request failed:", err.response?.data || err);
  } finally {
    setAuthToken(null); // clear token
  }
}
