import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/login",
        component: () => import("./Pages/Login.vue"),
    },
    {
        path: "/",
        component: () => import("./Pages/Dashboard.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/notes/crear",
        component: () => import("./Pages/CrearNota.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/register",
        component: () => import("./Pages/Register.vue"),
    },
];

// Crea el router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Configura el guardia de navegación
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token'); // O verifica según tu estado de Vuex

    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next({ path: '/login' });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;