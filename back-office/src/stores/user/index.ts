import type { User } from '@/types/user'

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

  const resetUser = () => {
    mutationUser(undefined)
  }

  return {
    getterUser,
    mutationUser,
    getUser,
    resetUser,
  }
})
