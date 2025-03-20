import type { Router } from 'vue-router'

import { useAuthStore } from '@/stores/auth'
import { useSalonsStore } from '@/stores/salons'

export const middlewares = (router: Router) => {
  router.beforeEach(async (to, from, next) => {
    const { isAuthenticated, getterMode } = useAuthStore()

    // userProfile().then(() => {
    //   const redirect = checkAccess(to, from)
    //   if (redirect) return next(redirect)

    //   let middlewareReturnedValue: boolean = true
    //   const metaArrays = Object.entries(from.meta)
    //   metaArrays.forEach((metaArray) => {
    //     const [metaKey, metaValue] = metaArray
    //     if (metaKey !== 'middlewares') return
    //     if (!Array.isArray(metaValue)) return
    //     middlewareReturnedValue = walkMiddlewares(metaValue, to, from)
    //   })
    //   next(middlewareReturnedValue)
    // })

    const requireAuth = checkAccess(to, from)
    if (requireAuth && !isAuthenticated) return next(requireAuth)

    const requiresOwnership = await checkOwnership(to)
    if (requiresOwnership) return next(requiresOwnership)

    const redirect = redirectOnAuthenticated(to, getterMode)
    if (redirect && isAuthenticated) return next(redirect)

    next()
  })
}

const checkAccess = (to: any, from: any) => {
  return to.meta.requiresAuth && !from.meta.requiresAuth ? { name: 'Login' } : null
}

const redirectOnAuthenticated = (to: any, mode: any) => {
  if (to.name === 'Login' || to.name === 'Registration') {
    if (mode === 'management') {
      return { name: 'ManagementDashboard' }
    }
    if (mode === 'store') {
      return { name: 'StoreDashboard' }
    }
    return { name: 'ModeSelect' }
  }
  return null
}

const checkOwnership = async (to: any) => {
  if (to.meta.requiresOwnership) {
    const salonsStore = useSalonsStore()
    if (!salonsStore.getterSalon) await salonsStore.getSalon({ id: to.params.id })
    console.log('🚀 ~ checkOwnership ~ getterSalon:', salonsStore.getterSalon)
    if (!salonsStore.getterSalon) {
      return { name: 'Unauthorized' }
    }
  }
  return null
}
