import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { Salon } from '@/types/salons'

export const useSalonsStore = defineStore('salons', () => {
  const salon = ref<Salon | undefined>()
  const salons = ref<Salon[]>()

  const getterSalon = computed<Salon | undefined>(() => salon.value)
  const getterSalons = computed<Salon[] | undefined>(() => salons.value)

  const mutationSalon = (newValue: Salon | undefined) => {
    salon.value = newValue
  }
  const mutationSalons = (newValue: Salon[] | undefined) => {
    salons.value = newValue
  }

  const getSalon = async (payload: { id: number }) => {
    const response = await api().salons.get(payload)
    mutationSalon(response.data)
  }
  const listSalons = async () => {
    const response = await api().salons.list()
    mutationSalons(response.data)
  }
  const createSalon = async (payload: Salon) => {
    // const response = await api().salons.create(payload)
    // mutationSalon(response.data)
  }

  return {
    getterSalon,
    getterSalons,
    getSalon,
    listSalons,
    createSalon,
  }
})
