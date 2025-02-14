import type { User, UserRoles } from '@/types/user'

import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

export const useUserStore = defineStore('user', () => {
  const user = ref<User | undefined>()
  const token = ref<string | null>(localStorage.getItem('token'))

  const getterUser = computed<User | undefined>(() => user.value)
  const getterToken = computed<string | null>(() => token.value)

  const mutationUser = (newValue: User | undefined) => {
    user.value = newValue
  }
  const mutationToken = (newValue: string | null) => {
    token.value = newValue
    localStorage.setItem('token', JSON.stringify(newValue))
  }

  const actionLogin = async (payload: { username: string; password: string }) => {
    const response = await api().user.login(payload)
    mutationUser(response.data.user)
    mutationToken(response.data.token)
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
  }

  const resetToken = () => {
    mutationToken(null)
    localStorage.removeItem('token')
  }

  return {
    getterUser,
    getterToken,
    mutationUser,
    mutationToken,
    actionLogin,
    actionLogout,
    actionRegister,
    resetUser,
    resetToken,
  }
})
