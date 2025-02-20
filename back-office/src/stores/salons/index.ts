import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { Salon, SalonCreatePayload, SalonUpdatePayload } from '@/types/salons'

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
  const createSalon = async (payload: SalonCreatePayload) => {
    // const response = await api().salons.create(payload)
    // mutationSalon(response.data)
  }
  const updateSalon = async (payload: SalonUpdatePayload) => {
    const response = await api().salons.update(payload)
    mutationSalon(response.data)
  }
  const deleteSalon = async (payload: { id: number }) => {
    await api().salons.delete(payload)
    resetSalon()
    listSalons()
  }

  const resetSalon = () => {
    mutationSalon(undefined)
  }
  const resetSalons = () => {
    mutationSalons(undefined)
  }

  return {
    getterSalon,
    getterSalons,
    getSalon,
    listSalons,
    createSalon,
    updateSalon,
    deleteSalon,
    resetSalon,
    resetSalons,
  }
})
