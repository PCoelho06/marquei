import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { Employee, Machine } from '@/types/resources'

export const useResourcesStore = defineStore('resources', () => {
  const employees = ref<Employee[] | undefined>()
  const machines = ref<Machine[] | undefined>()

  const getterEmployees = computed<Employee[] | undefined>(() => employees.value)
  const getterMachines = computed<Machine[] | undefined>(() => machines.value)

  const mutationEmployees = (newValue: Employee[]) => {
    employees.value = newValue
  }
  const mutationMachines = (newValue: Machine[]) => {
    machines.value = newValue
  }

  const getEmployees = async () => {
    const response = await api().employees.list()
    mutationEmployees(response.data)
  }
  const getMachines = async () => {
    const response = await api().machines.list()
    mutationMachines(response.data)
  }

  const resetEmployees = () => {
    employees.value = undefined
  }
  const resetMachines = () => {
    machines.value = undefined
  }

  return {
    getterEmployees,
    getterMachines,
    getEmployees,
    getMachines,
    resetEmployees,
    resetMachines,
  }
})
