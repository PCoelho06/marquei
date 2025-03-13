import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type {
  Salon,
  SalonCreatePayload,
  SalonUpdatePayload,
  BusinessHours,
  Service,
  Subscriptions,
} from '@/types/salons'

import type { Employee, Machine } from '@/types/resources'

export const useSalonsStore = defineStore('salons', () => {
  const salon = ref<Salon | undefined>()
  const salons = ref<Salon[]>()
  const businessHours = ref<BusinessHours[] | undefined>()
  const services = ref<Service[] | undefined>()
  const employees = ref<Employee[] | undefined>()
  const machines = ref<Machine[] | undefined>()

  const getterSalon = computed<Salon | undefined>(() => salon.value)
  const getterSalons = computed<Salon[] | undefined>(() => salons.value)
  const getterBusinessHours = computed<BusinessHours[] | undefined>(() => businessHours.value)
  const getterServices = computed<Service[] | undefined>(() => services.value)
  const getterEmployees = computed<Employee[] | undefined>(() => employees.value)
  const getterMachines = computed<Machine[] | undefined>(() => machines.value)

  const mutationSalon = (newValue: Salon | undefined) => {
    salon.value = newValue
  }
  const mutationSalons = (newValue: Salon[] | undefined) => {
    salons.value = newValue
  }
  const mutationBusinessHours = (newValue: BusinessHours[] | undefined) => {
    businessHours.value = newValue
  }
  const mutationServices = (newValue: Service[] | undefined) => {
    services.value = newValue
  }
  const mutationEmployees = (newValue: Employee[] | undefined) => {
    employees.value = newValue
  }
  const mutationMachines = (newValue: Machine[] | undefined) => {
    machines.value = newValue
  }

  const getSalon = async (payload: { id: number }) => {
    try {
      const response = await api().salons.get(payload)
      mutationSalon(response.data)
    } catch (error) {
      mutationSalon(undefined)
    }
  }
  const getBusinessHours = async (payload: { id: number }) => {
    const response = await api().salons.getBusinessHours(payload)
    mutationBusinessHours(response.data)
  }
  const getServices = async (payload: { id: number }) => {
    const response = await api().salons.getServices(payload)
    mutationServices(response.data)
  }
  const getEmployees = async (payload: { id: number }) => {
    const response = await api().salons.getEmployees(payload)
    mutationEmployees(response.data)
  }
  const getMachines = async (payload: { id: number }) => {
    const response = await api().salons.getMachines(payload)
    mutationMachines(response.data)
  }
  const listSalons = async () => {
    const response = await api().salons.list()
    mutationSalons(response.data)
  }
  const createSalon = async (payload: SalonCreatePayload) => {
    const response = await api().salons.create(payload)
    mutationSalon(response.data)
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
  const resetBusinessHours = () => {
    mutationBusinessHours(undefined)
  }
  const resetServices = () => {
    mutationServices(undefined)
  }
  const resetEmployees = () => {
    mutationEmployees(undefined)
  }
  const resetMachines = () => {
    mutationMachines(undefined)
  }

  return {
    getterSalon,
    getterSalons,
    getterBusinessHours,
    getterServices,
    getterEmployees,
    getterMachines,
    getSalon,
    getBusinessHours,
    getServices,
    getEmployees,
    getMachines,
    listSalons,
    createSalon,
    updateSalon,
    deleteSalon,
    resetSalon,
    resetSalons,
    resetBusinessHours,
    resetServices,
    resetEmployees,
    resetMachines,
  }
})
