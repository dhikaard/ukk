import { createRouter, createWebHistory } from 'vue-router'
import local from '../utils/local-storage';
import HomeView from '../views/HomeView.vue'
import History from '../views/HistoryView.vue'
import NotFound from '../views/NotFoundView.vue'
import Product from '../views/ProductDetail.vue'
import Login from '../views/Login.vue'
import AdminRoles from '../views/AdminRoles.vue'
import ManageProducts from '../views/ManageProducts.vue'
import Register from '../views/Register.vue'
import ManageTransactions from '@/views/ManageTransactions.vue'
import ManageBrandCtgr from '@/views/ManageBrandCtgr.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
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
    path: '/history',
    name: 'history',
    component: History
  },
  {
    path: '/product/:productCode',
    name: 'product',
    component: Product
  },
  {
    path: '/manage-admin-roles',
    name: 'adminRoles',
    component: AdminRoles,
    meta: {
      taskNames: ['manageAdmin'],
    }
  },
  {
    path: '/manage-products',
    name: 'manageProducts',
    component: ManageProducts,
    meta: {
      taskNames: ['manageProduct', 'manageAdmin'],
    }
  },
  {
    path: '/manage-transactions',
    name: 'manageTransactions',
    component: ManageTransactions,
    meta: {
      taskNames: ['manageAdmin', 'manageProduct'],
    }
  },
  {
    path: '/manage-brand-categories',
    name: 'manageBrandCtgr',
    component: ManageBrandCtgr,
    meta: {
      taskNames: [],
    }
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

router.beforeEach((to, from, next) => {
  const permissions = JSON.parse(local.get('permissions')) || [];
  const requiredTasks = to.meta.taskNames || [];

  if (requiredTasks.length > 0 && permissions) {
    const hasAccess = requiredTasks.some(task => permissions.includes(task));

    if (!hasAccess) {
      return next({ name: 'notFound' });
    }
  }

  next();
})

export default router