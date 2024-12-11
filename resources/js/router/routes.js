//Pages
const routes = [
    {
        path: "/",
        name: "esa.dashboard",
        component: () =>
            import(
                /* webpackChunkName: "Dashboard" */ "@/pages/Dashboard.vue"
            ),
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
];

export default routes;
