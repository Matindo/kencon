import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/user',
    name: 'User',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import('../views/User.vue')
  },
  {
    path: '/about',
    name: 'About',
    component: () => import('../views/About.vue')
  },
  {
    path: '/loader',
    name: 'Loader',
    component: () => import('../views/Loader.vue')
  },
  {
    path: '*',
    name: 'Not Found',
    component: () => import('../views/NotFound.vue')
  },
  {
    path: '/404',
    name: '404',
    component: () => import('../views/NotFound.vue')
  },
  { path: '/trader', name: 'Trader', component: () => import('../views/Trader.vue') }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
