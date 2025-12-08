import CreateCategory from '@/pages/Dashboard/Category/CreateCategory.vue'
import IndexCategory from '@/pages/Dashboard/Category/IndexCategory.vue'
import Dashboard from '@/pages/Dashboard/Dashboard.vue'
import CreateItem from '@/pages/Dashboard/Item/CreateItem.vue'
import IndexItem from '@/pages/Dashboard/Item/IndexItem.vue'
import DetailPurchase from '@/pages/Dashboard/Purchase/DetailPurchase.vue'
import IndexPurchase from '@/pages/Dashboard/Purchase/IndexPurchase.vue'
import Login from '@/pages/Login.vue'
import IndexWarehouse from '@/pages/Warehouse/IndexWarehouse.vue'
import { createRouter, createWebHistory } from 'vue-router'
import CreatePurchase from '@/pages/Dashboard/Purchase/CreatePurchase.vue'
import IndexSupplier from '@/pages/Dashboard/Supplier/IndexSupplier.vue'
import EditItem from '@/pages/Dashboard/Item/EditItem.vue'
import EditCategory from '@/pages/Dashboard/Category/EditCategory.vue'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: Dashboard,
      children: [
        {
          path: 'purchase',
          name: 'IndexPurchase',
          component: IndexPurchase,
        },
        {
          path: 'purchase/:id',
          name: 'DetailPurchase',
          component: DetailPurchase,
        },
        {
          path: 'purchase',
          name: 'CreatePurchase',
          component: CreatePurchase,
        },
        {
          path: '/category',
          name: 'IndexCategory',
          component: IndexCategory,
        },
        {
          path: '/create-category',
          name: 'CreateCategory',
          component: CreateCategory,
        },
        {
          path: '/edit-category/:id/:name',
          name: 'EditCategory',
          component: EditCategory,
        },
        {
          path: '/warehouse',
          name: 'IndexWarehouse',
          component: IndexWarehouse,
        },
        {
          path: '/item',
          name: 'IndexItem',
          component: IndexItem,
        },
        {
          path: '/create-item',
          name: 'CreateItem',
          component: CreateItem,
        },
        {
          path: '/edit-item/:id/:name/:category_id/:description',
          name: 'EditItem',
          component: EditItem,
        },
        {
          path: '/supplier',
          name: 'IndexSupplier',
          component: IndexSupplier,
        },
      ],
    },
  ],
})

export default router
