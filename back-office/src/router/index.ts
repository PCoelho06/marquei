import { createRouter, createWebHistory } from 'vue-router'

import HomeView from '../views/HomeView.vue'
import SignupView from '../views/Authentication/SignupView.vue'
import SigninView from '@/views/Authentication/SigninView.vue'
import DashboardView from '@/views/DashboardView.vue'
import RouterSalons from '@/views/Salons/RouterSalons.vue'
import SalonsView from '@/views/Salons/SalonsView.vue'
import SalonView from '@/views/Salons/SalonView.vue'
import HandleSalonView from '@/views/Salons/HandleSalonView.vue'
import HandleBusinessHoursView from '@/views/Salons/HandleBusinessHoursView.vue'
import ForfaitsView from '@/views/Subscription/ForfaitsView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/signup',
      name: 'signup',
      component: SignupView,
    },
    {
      path: '/signin',
      name: 'signin',
      component: SigninView,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
    },
    {
      path: '/salons',
      name: 'salons',
      component: RouterSalons,
      children: [
        {
          path: '',
          name: 'listSalons',
          component: SalonsView,
        },
        {
          path: ':id',
          name: 'getSalon',
          component: SalonView,
        },
        {
          path: 'registar',
          name: 'createSalon',
          component: HandleSalonView,
        },
        {
          path: ':id/editar',
          name: 'editSalon',
          component: HandleSalonView,
        },
        {
          path: ':id/handleBusinessHours',
          name: 'handleBusinessHours',
          component: HandleBusinessHoursView,
        },
      ],
    },
    {
      path: '/adherir',
      name: 'subscribe',
      component: ForfaitsView,
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('access_token')

  if (to.name !== 'home' && to.name !== 'signin' && to.name !== 'signup' && !isAuthenticated) {
    next({ name: 'signin' })
  } else if (
    (to.name === 'home' || to.name === 'signin' || to.name === 'signup') &&
    isAuthenticated
  ) {
    next({ name: 'dashboard' })
  } else {
    next()
  }
})

export default router
