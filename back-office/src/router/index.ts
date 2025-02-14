import { createRouter, createWebHistory } from 'vue-router'

import { useUserStore } from '@/stores/user'

import HomeView from '../views/HomeView.vue'
import SignupView from '../views/Authentication/SignupView.vue'
import SigninView from '@/views/Authentication/SigninView.vue'
import DashboardView from '@/views/DashboardView.vue'

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
  ],
})

router.beforeEach((to, from, next) => {
  const userStore = useUserStore()
  const isAuthenticated = !!userStore.getterToken

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
