import type { User, UserRoles } from '@/types/user'

import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

export const useUserStore = defineStore('user', () => {
  const user = ref<User | undefined>()
  const token = ref<string | undefined>(localStorage.getItem('access_token') || undefined)

  const getterUser = computed<User | undefined>(() => user.value)
  const getterToken = computed<string | undefined>(() => token.value)

  const mutationUser = (newValue: User | undefined) => {
    user.value = newValue
    localStorage.setItem('userID', JSON.stringify(newValue?.id))
  }
  const mutationToken = (newValue: string | undefined) => {
    token.value = newValue
    if (newValue) localStorage.setItem('access_token', newValue)
  }

  const getUser = async (payload: { id: number }) => {
    const response = await api().user.get(payload)
    mutationUser(response.data)
  }

  const actionLogin = async (payload: { username: string; password: string }) => {
    const response = await api().user.login(payload)
    mutationUser(response.data.user)
    mutationToken(response.data.access_token)
    localStorage.setItem('refresh_token', response.data.refresh_token)
  }

  const actionLogout = () => {
    resetUser()
    resetToken()
  }

  const actionRegister = async (payload: { email: string; password: string; role: UserRoles }) => {
    const response = await api().user.register(payload)
    mutationUser(response.data.user)
    mutationToken(response.data.token)
  }

  const resetUser = () => {
    mutationUser(undefined)
    localStorage.removeItem('userID')
  }

  const resetToken = () => {
    mutationToken(undefined)
    localStorage.removeItem('token')
  }

  return {
    getterUser,
    getterToken,
    mutationUser,
    mutationToken,
    getUser,
    actionLogin,
    actionLogout,
    actionRegister,
    resetUser,
    resetToken,
  }
})
