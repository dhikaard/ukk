import { createRouter, createWebHistory } from 'vue-router'
import { useHomeViewStore } from '@/stores/home-view.store'
import HomeView from '@/views/HomeView.vue'
import History from '@/views/HistoryView.vue'
import NotFound from '@/views/NotFoundView.vue'
import TermsView from '@/views/TermsView.vue'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { hideNavbarFooter: true }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { hideNavbarFooter: true }
  },
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/history',
    name: 'history',
    component: History
  },
  {
    path: '/terms',
    name: 'terms',
    component: TermsView
  },
  {
    path: '/product/:code/:slug',
    name: 'detailProduct',
    component: () => import('@/views/DetailProduct.vue')
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'notFound',
    component: NotFound,
    meta: { hideNavbarFooter: true }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router