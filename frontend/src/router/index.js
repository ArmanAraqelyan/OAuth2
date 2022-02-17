import { createRouter, createWebHistory } from 'vue-router'
import Authorization from '../views/Authorization.vue'

const routes = [
    {
        path: '/',
        name: 'Authorization',
        component: Authorization
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () =>
            import ('../views/Profile.vue')
    }
]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
})

export default router