import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import History from '../views/HistoryView.vue'
import NotFound from '../views/NotFoundView.vue'
import Product from '../views/ProductDetail.vue'
import Login from '../views/Login.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    // children: [
    //   {
    //     path: '/home',
    //     name: 'home',
    //     component: HomeView,
    //   }
    // ]
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { hideNavbarFooter: true }
  },
  {
    path: '/history',
    name: 'history',
    component: History
  },
  {
    path: '/product',
    name: 'product',
    component: Product
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