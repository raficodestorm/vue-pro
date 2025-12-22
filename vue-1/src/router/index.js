import { createRouter, createWebHistory } from "vue-router";

// Layouts
import UserLayout from "../layouts/UserLayout.vue";
import AdminLayout from "../layouts/AdminLayout.vue";
import CounterLayout from "../layouts/CounterLayout.vue";

// User Pages
import Home from "../pages/user/Home.vue";
import Register from "../pages/auth/Register.vue";

// Admin Pages
import Admin from "../pages/dashboard/Admin.vue";
import BusIndex from "../pages/admin/bus/BusIndex.vue";
import BusAdd from "../pages/admin/bus/BusAdd.vue";
import BusEdit from "../pages/admin/bus/BusEdit.vue";
import BustypeAdd from "../pages/admin/bustype/BustypeAdd.vue";
import BustypeIndex from "../pages/admin/bustype/BustypeIndex.vue";
import RouteAdd from "../pages/admin/route/RouteAdd.vue";
import RouteIndex from "../pages/admin/route/RouteIndex.vue";
import RouteEdit from "../pages/admin/route/RouteEdit.vue";
import LocationIndex from "../pages/admin/location/LocationIndex.vue";
import LocationAdd from "../pages/admin/location/LocationAdd.vue";
import LocationEdit from "../pages/admin/location/LocationEdit.vue";

//Counter Pages
import Counter from "../pages/dashboard/Counter.vue";
import ControllerLayout from "../layouts/ControllerLayout.vue";
import Controller from "../pages/dashboard/Controller.vue";

// ROUTES
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
      { path: "bus/edit/:id", name: "editbus", component: BusEdit },
      
      { path: "bustype/add", name: "addbustype", component: BustypeAdd },
      { path: "bustype/index", name: "allbustype", component: BustypeIndex },

      { path: "route/add", name: "addroute", component: RouteAdd },
      { path: "route/index", name: "allroute", component: RouteIndex },
      { path: "route/edit/:id", name: "editroute", component: RouteEdit },

      { path: "location/index", name: "alllocation", component: LocationIndex },
      { path: "location/add", name: "addlocation", component: LocationAdd },
      { path: "location/edit/:id", name: "editlocation", component: LocationEdit }
    ],
  },

  {
    path: "/counter",
    component: CounterLayout,
    children: [
      { path: "", component: Counter },
      
    ],
  },

  {
    path: "/controller",
    component: ControllerLayout,
    children: [
      { path: "", component: Controller },
      
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
//  GLOBAL AUTH GUARD
// ===================================
router.beforeEach((to, from, next) => {
  const role = localStorage.getItem("role");
  const token = localStorage.getItem("auth_token");

  const publicRoutes = ["/", "/register", "/login"];

  // Public routes
  if (publicRoutes.includes(to.path)) return next();

  // Not logged in
  if (!token) return next("/");

  // Role restrictions
  if (to.path.startsWith("/admin") && role !== "admin") return next("/");
  if (to.path.startsWith("/controller") && role !== "controller") return next("/");
  if (to.path.startsWith("/counter") && role !== "counter") return next("/");
  if (to.path.startsWith("/user") && role !== "user") return next("/");

  next();
});


export default router;
