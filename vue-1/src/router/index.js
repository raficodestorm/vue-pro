import { createRouter, createWebHistory } from "vue-router";
import UserLayout from "../layouts/UserLayout.vue";
import AdminLayout from "../layouts/AdminLayout.vue";
import Home from "../pages/user/Home.vue";
import Register from "../pages/auth/Register.vue";
import Admin from "../pages/dashboard/Admin.vue";
import BusIndex from "../pages/admin/bus/BusIndex.vue";
import BusAdd from "../pages/admin/bus/BusAdd.vue";




const routes = [
    {
        path: "/",
        component: UserLayout,
        children: [
            { path: "", component: Home },
            { path: "register", name: "Register", component: Register },
            
        ]
    },

    {
        path: "/admin",
        component: AdminLayout,
        children: [
            { path: "", component: Admin },
            { path: "bus/index", name: "allbuses", component: BusIndex },
            { path: "bus/add", name: "addbus", component: BusAdd },
            
        ]
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;