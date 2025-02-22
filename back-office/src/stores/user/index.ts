import type { User, UserRoles } from '@/types/user'

import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

export const useUserStore = defineStore('user', () => {
  const user = ref<User | undefined>()

  const getterUser = computed<User | undefined>(() => user.value)

  const mutationUser = (newValue: User | undefined) => {
    user.value = newValue
  }

  const getUser = async (payload: { id: number }) => {
    const response = await api().user.get(payload)
    mutationUser(response.data)
  }

  const actionLogin = async (payload: { username: string; password: string }) => {
    const response = await api().user.login(payload)
    mutationUser(response.data.user)
    localStorage.setItem('access_token', response.data.access_token)
    localStorage.setItem('refresh_token', response.data.refresh_token)
  }

  const actionLogout = () => {
    resetUser()
    localStorage.removeItem('access_token')
    localStorage.removeItem('refresh_token')
  }

  const actionRegister = async (payload: { email: string; password: string; role: UserRoles }) => {
    const response = await api().user.register(payload)
    mutationUser(response.data.user)
    localStorage.setItem('access_token', response.data.access_token)
    localStorage.setItem('refresh_token', response.data.refresh_token)
  }

  const resetUser = () => {
    mutationUser(undefined)
  }

  return {
    getterUser,
    mutationUser,
    getUser,
    actionLogin,
    actionLogout,
    actionRegister,
    resetUser,
  }
})
