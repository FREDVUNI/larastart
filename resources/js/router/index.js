import { createRouter, createWebHistory } from "vue-router";

// Views
import Dashboard from "../pages/Dashboard";
import Profile from "../pages/Profile";
import Users from "../pages/Users";

// WebHistory
const history = createWebHistory();

const routes = [
    {
        path: "/dashboard",
        component: Dashboard,
        name: "dashboard",
        meta: {
            title: "Dashboard",
        },
    },
    {
        path: "/profile",
        component: Profile,
        name: "profile",
        meta: {
            title: "Profile",
        },
    }, 
    {
        path: "/users",
        component: Users,
        name: "Users",
        meta: {
            title: "Users",
        },
    }
];

const router = createRouter({
    history,
    routes,
});

export default router;