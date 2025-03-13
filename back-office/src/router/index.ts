import { createRouter, createWebHistory } from 'vue-router'

import { routes } from '@/composables/routing'

import { middlewares } from './middlewares'

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

middlewares(router)

// router.beforeEach((to, from, next) => {
//   const { isAuthenticated, getterMode } = useAuthStore()

//   if (
//     isAuthenticated &&
//     (to.name === 'Login' || to.name === 'Registration' || to.name === 'Home')
//   ) {
//     if (getterMode === 'management') {
//       return next({ name: 'ManagementDashboard' })
//     } else if (getterMode === 'store') {
//       return next({ name: 'StoreDashboard' })
//     } else {
//       return next({ name: 'ModeSelect' })
//     }
//   }

//   if (to.meta.requiresAuth && !isAuthenticated) {
//     return next({ name: 'Login' })
//   }

//   // if (to.meta.permission && !authStore.permissions.includes(to.meta.permission)) {
//   //   return next({ name: 'unauthorized' })
//   // }

//   next()
// })

export default router
