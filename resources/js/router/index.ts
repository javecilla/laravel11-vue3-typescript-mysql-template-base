import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "Home",
        component: () => import("@/pages/HomePage.vue"),
        meta: {
            title: "Home",
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Global navigation guard to update the page title
router.beforeEach((to, from, next) => {
    const title = to.meta.title as string | undefined; // Get the title from the route meta
    if (title) {
        document.title = `${title} | ${import.meta.env.VITE_APP_NAME}`; // Update the document title
    } else {
        document.title = import.meta.env.VITE_APP_NAME as string; // Fallback to the app name
    }
    next();
});

export default router;
