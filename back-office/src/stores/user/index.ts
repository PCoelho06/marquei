import type { User, UserRoles } from '@/types/user'

import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

export const useUserStore = defineStore('user', () => {
  const user = ref<User | undefined>()
  const token = ref<string | undefined>()

  const getterUser = computed<User | undefined>(() => user.value)
  const getterToken = computed<string | undefined>(() => token.value)

  const mutationUser = (newValue: User | undefined) => {
    user.value = newValue
  }
  const mutationToken = (newValue: string | undefined) => {
    token.value = newValue
  }

  const actionLogin = async (payload: { username: string; password: string }) => {
    const response = await api().user.login(payload)
    mutationUser(response.data.user)
    mutationToken(response.token)
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
    mutationToken(undefined)
  }

  return {
    getterUser,
    getterToken,
    mutationUser,
    mutationToken,
    actionLogin,
    actionRegister,
    resetUser,
    resetToken,
  }
})
