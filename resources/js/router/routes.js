//Pages
import { ROLES } from '../helpers/roles';
const routes = [
    {
        path: "/",
        name: "esa.dashboard",
        component: () =>
            import(
                /* webpackChunkName: "Dashboard" */ "@/pages/Dashboard.vue"
            ),
        meta: {
            requiresAuth: true,
            allowedRoles: [ROLES.ADMIN]
        }
    },
    {
        path: "/settings",
        name: "esa.settings",
        component: () =>
            import(/* webpackChunkName: "Settings" */ "@/pages/Settings.vue"),
    },
    {
        path: "/dynmic-reports",
        name: "esa.dynamic-reports",
        component: () =>
            import(/* webpackChunkName: "DynamicReports" */ "@/pages/reports/DynamicReports.vue"),
    },
    {
        path: "/reports",
        name: "esa.reports",
        component: () =>
            import(/* webpackChunkName: "Reports" */ "@/pages/reports/Reports.vue"),
        meta: {
            requiresAuth: true,
            allowedRoles: [ROLES.ADMIN]
        }
    },
    {
        path: "/login",
        name: "esa.login",
        component: () =>
            import(/* webpackChunkName: "Login" */ "@/pages/Login.vue"),
        meta: {
            requiresAuth: false,
        }
    },
];

export default routes;
