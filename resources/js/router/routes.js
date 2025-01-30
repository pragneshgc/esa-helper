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
    {
        path: "/reports",
        name: "esa.reports",
        component: () =>
            import(/* webpackChunkName: "Reports" */ "@/pages/reports/Reports.vue"),
    },
];

export default routes;
