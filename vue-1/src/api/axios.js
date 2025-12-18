import axios from "axios";

const api = axios.create({
  baseURL: "https://runstarss.safiulrafi.top/laravel/api",
  withCredentials: false,
});

// 🔥 Page refresh হলেও token attach হবে
const token = localStorage.getItem("auth_token");
if (token) {
  api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
}

export default api;
