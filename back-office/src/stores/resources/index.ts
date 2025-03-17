import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { CreateResourcePayload, Resource, UpdateResourcePayload } from '@/types/resources'

export const useResourcesStore = defineStore('resources', () => {
  const resources = ref<Resource[] | undefined>()
  const employees = ref<Resource[] | undefined>()
  const machines = ref<Resource[] | undefined>()

  const getterResources = computed<Resource[] | undefined>(() => resources.value)
  const getterEmployees = computed<Resource[] | undefined>(() => employees.value)
  const getterMachines = computed<Resource[] | undefined>(() => machines.value)

  const mutationResources = (newValue: Resource[]) => {
    resources.value = newValue
  }
  const mutationEmployees = (newValue: Resource[]) => {
    employees.value = newValue
  }
  const mutationMachines = (newValue: Resource[]) => {
    machines.value = newValue
  }

  const getResources = async () => {
    const response = await api().resources.listAll()
    mutationResources(response.data)
  }
  const getEmployees = async () => {
    const response = await api().resources.listByType({ type: 'employee' })
    mutationEmployees(response.data)
  }
  const getMachines = async () => {
    const response = await api().resources.listByType({ type: 'machine' })
    mutationMachines(response.data)
  }
  const createResource = async (payload: CreateResourcePayload) => {
    const response = await api().resources.create(payload)
    mutationResources([...(resources.value || []), response.data])
    if (payload.type === 'employee') {
      mutationEmployees([...(employees.value || []), response.data])
    } else {
      mutationMachines([...(machines.value || []), response.data])
    }
  }
  const updateResource = async (payload: UpdateResourcePayload) => {
    const response = await api().resources.update(payload)
    const updatedResources = (resources.value || []).map((resource) =>
      resource.id === payload.id ? response.data : resource,
    )
    mutationResources(updatedResources)
    if (payload.type === 'employee') {
      const updatedEmployees = (employees.value || []).map((resource) =>
        resource.id === payload.id ? response.data : resource,
      )
      mutationEmployees(updatedEmployees)
    } else {
      const updatedMachines = (machines.value || []).map((resource) =>
        resource.id === payload.id ? response.data : resource,
      )
      mutationMachines(updatedMachines)
    }
  }
  const deleteResource = async (payload: { id: number }) => {
    await api().resources.delete(payload)
    const updatedResources = (resources.value || []).filter(
      (resource) => resource.id !== payload.id,
    )
    mutationResources(updatedResources)
    if (employees.value) {
      const updatedEmployees = (employees.value || []).filter(
        (resource) => resource.id !== payload.id,
      )
      mutationEmployees(updatedEmployees)
    }
    if (machines.value) {
      const updatedMachines = (machines.value || []).filter(
        (resource) => resource.id !== payload.id,
      )
      mutationMachines(updatedMachines)
    }
  }

  const resetResources = () => {
    resources.value = undefined
  }
  const resetEmployees = () => {
    employees.value = undefined
  }
  const resetMachines = () => {
    machines.value = undefined
  }

  return {
    getterResources,
    getterEmployees,
    getterMachines,
    getResources,
    getEmployees,
    getMachines,
    createResource,
    updateResource,
    deleteResource,
    resetResources,
    resetEmployees,
    resetMachines,
  }
})
