import { createRouter, createWebHistory } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

import HomeView from '../views/HomeView.vue'
import SignupView from '../views/Authentication/SignupView.vue'
import SigninView from '@/views/Authentication/SigninView.vue'
import ManagementDashboard from '@/views/Management/ManagementDashboard.vue'
import RouterSalons from '@/views/Management/Salons/RouterSalons.vue'
import SalonsView from '@/views/Management/Salons/SalonsView.vue'
import SalonView from '@/views/Management/Salons/SalonView.vue'
import HandleSalonView from '@/views/Management/Salons/HandleSalonView.vue'
import HandleBusinessHoursView from '@/views/Management/Salons/HandleBusinessHoursView.vue'
import ForfaitsView from '@/views/Subscription/ForfaitsView.vue'
import RouterResources from '@/views/Management/Resources/RouterResources.vue'
import ResourcesView from '@/views/Management/Resources/ResourcesView.vue'
import ModeSelectView from '@/views/Authentication/ModeSelectView.vue'
import NotFound from '@/views/Errors/NotFound.vue'
import RouterManagement from '@/views/Management/RouterManagement.vue'
import RouterStore from '@/views/Store/RouterStore.vue'
import StoreDashboard from '@/views/Store/StoreDashboard.vue'
import AgendaView from '@/views/Store/Agenda/AgendaView.vue'

let routes = [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/registar', name: 'Signup', component: SignupView },
  { path: '/aceder', name: 'Signin', component: SigninView },
  {
    path: '/selecionar-modo',
    name: 'ModeSelect',
    component: ModeSelectView,
    meta: { requiresAuth: true },
  },
  {
    path: '/adherir',
    name: 'Subscription',
    component: ForfaitsView,
  },
]

const managementRoutes = [
  {
    path: '/gestao',
    name: 'Management',
    component: RouterManagement,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'ManagementDashboard',
        component: ManagementDashboard,
        meta: { requiresAuth: true },
      },
      {
        path: '/saloes',
        name: 'Salons',
        component: RouterSalons,
        meta: { requiresAuth: true },
        children: [
          {
            path: '',
            name: 'ListSalons',
            component: SalonsView,
          },
          {
            path: ':id',
            name: 'GetSalon',
            component: SalonView,
          },
          {
            path: 'registar',
            name: 'CreateSalon',
            component: HandleSalonView,
          },
          {
            path: ':id/editar',
            name: 'EditSalon',
            component: HandleSalonView,
          },
          {
            path: ':id/horarios-de-funcionamento',
            name: 'HandleBusinessHours',
            component: HandleBusinessHoursView,
          },
        ],
      },
      {
        path: '/recursos',
        name: 'Resources',
        component: RouterResources,
        meta: { requiresAuth: true },
        children: [
          {
            path: '',
            name: 'ListResources',
            component: ResourcesView,
          },
        ],
      },
    ],
  },
]

const storeRoutes = [
  {
    path: '/salao',
    name: 'Store',
    component: RouterStore,
    children: [
      {
        path: '',
        name: 'StoreAgenda',
        component: AgendaView,
        meta: { requiresAuth: true },
      },
      {
        path: 'painel',
        name: 'StoreDashboard',
        component: StoreDashboard,
        meta: { requiresAuth: true },
      },
    ],
  },
]

routes = [
  ...routes,
  ...storeRoutes,
  ...managementRoutes,
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return { el: to.hash, behavior: 'smooth' }
    } else {
      return { top: 0, behavior: 'smooth' }
    }
  },
})

router.beforeEach((to, from, next) => {
  const { isAuthenticated, getterMode } = useAuthStore()

  if (isAuthenticated && (to.name === 'Signin' || to.name === 'Signup' || to.name === 'Home')) {
    if (getterMode === 'management') {
      return next({ name: 'ManagementDashboard' })
    } else if (getterMode === 'store') {
      return next({ name: 'StoreDashboard' })
    } else {
      return next({ name: 'ModeSelect' })
    }
  }

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'Signin' })
  }

  // if (to.meta.permission && !authStore.permissions.includes(to.meta.permission)) {
  //   return next({ name: 'unauthorized' })
  // }

  next()
})

// router.beforeEach(async (to, from, next) => {
//   const isAuthenticated = !!localStorage.getItem('access_token')

//   if (to.name !== 'Home' && to.name !== 'Signin' && to.name !== 'Signup' && !isAuthenticated) {
//     next({ name: 'Signin' })
//   } else if (
//     (to.name === 'Home' || to.name === 'Signin' || to.name === 'Signup') &&
//     isAuthenticated
//   ) {
//     next({ name: 'Dashboard' })
//   } else {
//     next()
//   }
// })

export default router
