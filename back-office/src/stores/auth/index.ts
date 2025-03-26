import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'
import { decoders } from '@/utils'

import type { Salon } from '@/types/salons'
import type { User, Permission } from '@/types/user'
import type { JWTPayload } from '@/types'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | undefined>()
  const mode = ref<'management' | 'store' | undefined>()
  const accessToken = ref<string | undefined>(localStorage.getItem('access_token') ?? undefined)
  const refreshToken = ref<string | undefined>(localStorage.getItem('refresh_token') ?? undefined)
  const hasSalons = ref<boolean>(false)
  //   const role = ref<string | undefined>()
  //   const currentSalon = ref<Salon | undefined>()
  //   const permissions = ref<Permission[] | undefined>()

  const getterUser = computed(() => user.value)
  const getterMode = computed(() => mode.value)
  const getterAccessToken = computed(() => accessToken.value)
  const getterRefreshToken = computed(() => refreshToken.value)
  const getterHasSalons = computed(() => hasSalons.value)
  //   const getterRole = computed(() => role.value)
  //   const getterCurrentSalon = computed(() => currentSalon.value)
  //   const getterPermissions = computed(() => permissions.value)
  const isAuthenticated = computed(() => !!accessToken.value)

  const mutationUser = (newValue: User | undefined) => {
    user.value = newValue
  }
  const mutationMode = (newValue: 'management' | 'store' | undefined) => {
    mode.value = newValue
  }
  const mutationAccessToken = (newValue: string | undefined) => {
    accessToken.value = newValue
    if (newValue) {
      localStorage.setItem('access_token', newValue)
    } else {
      localStorage.removeItem('access_token')
    }
  }
  const mutationRefreshToken = (newValue: string | undefined) => {
    refreshToken.value = newValue
    if (newValue) {
      localStorage.setItem('refresh_token', newValue)
    } else {
      localStorage.removeItem('refresh_token')
    }
  }
  const mutationHasSalons = (newValue: boolean) => {
    hasSalons.value = newValue
  }
  //   const mutationRole = (newValue: string | undefined) => {
  //     role.value = newValue
  //   }
  //   const mutationCurrentSalon = (newValue: Salon | undefined) => {
  //     currentSalon.value = newValue
  //   }
  //   const mutationPermissions = (newValue: Permission[] | undefined) => {
  //     permissions.value = newValue
  //   }

  const actionLogin = async (payload: { username: string; password: string }) => {
    const response = await api().auth.login(payload)
    mutationUser(response.data.user)
    mutationAccessToken(response.data.access_token)
    mutationHasSalons(response.data.hasSalons)
  }

  const actionLogout = async () => {
    await api().auth.logout()
    resetUser()
    resetAccessToken()
    resetRefreshToken()
  }

  const actionRegister = async (payload: { email: string; password: string }) => {
    const response = await api().auth.register(payload)
    mutationUser(response.data.user)
    mutationAccessToken(response.data.access_token)
    mutationRefreshToken(response.data.refresh_token)
  }

  const actionRefresh = async () => {
    const response = await api().auth.refresh()
    mutationAccessToken(response.data.access_token)
    mutationRefreshToken(response.data.refresh_token)
  }

  const actionSelectMode = async (payload: { mode: 'management' | 'store'; salonId?: number }) => {
    const response = await api().auth.selectMode(payload)
    mutationMode(payload.mode)
    mutationAccessToken(response.data.access_token)
    mutationRefreshToken(response.data.refresh_token)
  }

  const initialize = async () => {
    if (getterAccessToken.value) {
      const decoded: JWTPayload = decoders.decodeJWT(getterAccessToken.value)
      if (decoded) {
        mutationMode(decoded.currentMode)
      }
    }
  }

  const fetchUser = async () => {
    const response = await api().auth.fetchUser()
    mutationUser(response.data)
  }

  const resetUser = () => {
    mutationUser(undefined)
  }
  const resetMode = () => {
    mutationMode(undefined)
  }
  const resetAccessToken = () => {
    mutationAccessToken(undefined)
  }
  const resetRefreshToken = () => {
    mutationRefreshToken(undefined)
  }
  const resetHasSalons = () => {
    mutationHasSalons(false)
  }
  //   const resetRole = () => {
  //     mutationRole(undefined)
  //   }
  //   const resetCurrentSalon = () => {
  //     mutationCurrentSalon(undefined)
  //   }
  //   const resetPermissions = () => {
  //     mutationPermissions(undefined)
  //   }

  return {
    getterUser,
    getterMode,
    getterAccessToken,
    getterRefreshToken,
    getterHasSalons,
    // getterRole,
    // getterCurrentSalon,
    // getterPermissions,
    isAuthenticated,
    mutationUser,
    mutationMode,
    mutationAccessToken,
    mutationRefreshToken,
    mutationHasSalons,
    // mutationRole,
    // mutationCurrentSalon,
    // mutationPermissions,
    actionLogin,
    actionLogout,
    actionRegister,
    actionRefresh,
    actionSelectMode,
    initialize,
    fetchUser,
    resetUser,
    resetMode,
    resetAccessToken,
    resetRefreshToken,
    resetHasSalons,
    // resetRole,
    // resetCurrentSalon,
    // resetPermissions,
  }
})
