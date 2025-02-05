import { createRouter, createWebHistory } from 'vue-router'
const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Login.vue'),
    meta: { hideNavbarFooter: true }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import('@/views/Register.vue'),
    meta: { hideNavbarFooter: true }
  },
  {
    path: '/',
    name: 'home',
    component: () => import('@/views/HomeView.vue')
  },
  {
    path: '/rent',
    name: 'rent',
    component: () => import('@/views/RentView.vue')
  },
  {
    path: '/history',
    name: 'history',
    component: () => import('@/views/HistoryView.vue') 
  },
  {
    path: '/terms',
    name: 'terms',
    component: () => import('@/views/TermsView.vue')
  },
  {
    path: '/product/:code/:slug',
    name: 'detailProduct',
    component: () => import('@/views/DetailProduct.vue')
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'notFound',
    component: () => import('@/views/NotFoundView.vue'),
    meta: { hideNavbarFooter: true }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router