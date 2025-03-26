import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { Salon, SalonCreatePayload, SalonUpdatePayload, BusinessHours } from '@/types/salons'
import type { BaseDeletePayload, BaseGetPayload, ListSettings } from '@/types'

export const useSalonsStore = defineStore('salons', () => {
  /**
   * @constant salon               <Salon | undefined>
   * @constant salonList           <Salon[] | undefined>
   * @constant salonSettings       <ListSettings | undefined>
   */
  const salon = ref<Salon | undefined>()
  const salonList = ref<Salon[] | undefined>()
  const salonSettings = ref<ListSettings | undefined>()

  /**
   * @constant getterSalon         <Salon | undefined>
   * @constant getterSalonList     <Salon[] | undefined>
   * @constant getterSalonSettings <ListSettings | undefined>
   */
  const getterSalon = computed<Salon | undefined>(() => salon.value)
  const getterSalonList = computed<Salon[] | undefined>(() => salonList.value)
  const getterSalonSettings = computed<ListSettings | undefined>(() => salonSettings.value)

  /**
   * @function mutationSalon             (value: Salon | undefined) => void
   * @function mutationSalonList         (value: Salon[] | undefined) => void
   * @function mutationSalonSettings     (value: ListSettings | undefined) => void
   */
  const mutationSalon = (newValue: Salon | undefined) => {
    salon.value = newValue
  }
  const mutationSalonList = (newValue: Salon[] | undefined) => {
    salonList.value = newValue
  }
  const mutationSalonSettings = (newValue: ListSettings | undefined) => {
    salonSettings.value = newValue
  }

  const getSalon = async (payload: BaseGetPayload) => {
    try {
      const response = await api().salons.get(payload)
      mutationSalon(response.data)
    } catch (error) {
      mutationSalon(undefined)
    }
  }
  const searchSalons = async (httpQuery: object) => {
    const response = await api().salons.search(httpQuery)
    mutationSalonList(response.data.salons)
    mutationSalonSettings(response.data.settings)
  }
  const createSalon = async (payload: SalonCreatePayload) => {
    const response = await api().salons.create(payload)
    mutationSalon(response.data)
  }
  const updateSalon = async (payload: SalonUpdatePayload) => {
    const response = await api().salons.update(payload)
    mutationSalon(response.data)
  }
  const deleteSalon = async (payload: BaseDeletePayload) => {
    await api().salons.delete(payload)
    searchSalons(payload)
    resetSalon()
  }

  const resetSalon = () => {
    mutationSalon(undefined)
  }
  const resetSalons = () => {
    mutationSalonList(undefined)
  }
  const resetSalonSettings = () => {
    mutationSalonSettings(undefined)
  }

  return {
    getterSalon,
    getterSalonList,
    getterSalonSettings,
    getSalon,
    searchSalons,
    createSalon,
    updateSalon,
    deleteSalon,
    resetSalon,
    resetSalons,
    resetSalonSettings,
  }
})
