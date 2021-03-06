import Vue from 'vue'
import VueRouter from 'vue-router'
import List from './views/List.vue'
import Identify from './views/Identify.vue'
import Register from './views/Register.vue'
import NotFound from './views/NotFound.vue'

Vue.use(VueRouter)

const routes = [{
        path: '/',
        name: 'List',
        component: List
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/identify',
        name: 'Identify',
        component: Identify
    },
    {
        path: '*',
        redirect: '/404'
    },
    {
        path: '/404',
        component: NotFound
    }
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router
