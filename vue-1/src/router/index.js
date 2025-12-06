import { createRouter, createWebHistory } from "vue-router";
import UserLayout from "../layouts/UserLayout.vue";
import Home from "../pages/user/Home.vue";




const routes = [
    {
        path: "/",
        component: UserLayout,
        children: [
            { path: "", component: Home },
            
        ]
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;