import { createRouter, createWebHistory } from "vue-router";

// Layouts
import UserLayout from "../layouts/UserLayout.vue";
import AdminLayout from "../layouts/AdminLayout.vue";

// User Pages
import Home from "../pages/user/Home.vue";
import Register from "../pages/auth/Register.vue";

// Admin Pages
import Admin from "../pages/dashboard/Admin.vue";
import BusIndex from "../pages/admin/bus/BusIndex.vue";
import BusAdd from "../pages/admin/bus/BusAdd.vue";
import BustypeAdd from "../pages/admin/bustype/BustypeAdd.vue";
import BustypeIndex from "../pages/admin/bustype/BustypeIndex.vue";

// ===================================
// ROUTES
// ===================================
const routes = [
  {
    path: "/",
    component: UserLayout,
    children: [
      { path: "", component: Home },
      { path: "register", name: "Register", component: Register },
    ],
  },

  {
    path: "/admin",
    component: AdminLayout,
    children: [
      { path: "", component: Admin },
      { path: "bus/index", name: "allbuses", component: BusIndex },
      { path: "bus/add", name: "addbus", component: BusAdd },
      { path: "bustype/add", name: "addbustype", component: BustypeAdd },
      { path: "bustype/index", name: "allbustype", component: BustypeIndex },
    ],
  },
];

// ===================================
// CREATE ROUTER
// ===================================
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// ===================================
// 🔥 GLOBAL AUTH GUARD
// ===================================
router.beforeEach((to, from, next) => {
  const role = localStorage.getItem("role");

  // Public Routes
  if (to.path === "/" || to.path === "/register") {
    return next();
  }

  // Not logged in → redirect to home
  if (!role) return next("/");

  // Role protections
  if (to.path.startsWith("/admin") && role !== "admin") return next("/");
  if (to.path.startsWith("/controller") && role !== "controller") return next("/");
  if (to.path.startsWith("/counter_manager") && role !== "counter_manager") return next("/");
  if (to.path.startsWith("/user") && role !== "user") return next("/");

  next();
});

export default router;
