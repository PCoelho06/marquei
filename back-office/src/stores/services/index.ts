import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type {
  Service,
  ServiceQuery,
  ServiceCreatePayload,
  ServiceUpdatePayload,
} from '@/types/services'

import type { BaseDeletePayload, BaseGetPayload, ListSettings } from '@/types'

export const useServicesStore = defineStore('services', () => {
  /**
   * @constant service               <Service | undefined>
   * @constant serviceList           <Service[] | undefined>
   * @constant serviceSettings       <ListSettings | undefined>
   * @constant query                  <ServiceQuery | undefined>
   */
  const service = ref<Service | undefined>()
  const serviceList = ref<Service[] | undefined>()
  const serviceSettings = ref<ListSettings | undefined>()
  const query = ref<ServiceQuery | undefined>()

  /**
   * @constant getterService         <Service | undefined>
   * @constant getterServiceList     <Service[] | undefined>
   * @constant getterServiceSettings <ListSettings | undefined>
   * @constant getterQuery            <ServiceQuery | undefined>
   */
  const getterService = computed<Service | undefined>(() => service.value)
  const getterServiceList = computed<Service[] | undefined>(() => serviceList.value)
  const getterServiceSettings = computed<ListSettings | undefined>(() => serviceSettings.value)
  const getterQuery = computed<ServiceQuery | undefined>(() => query.value)

  /**
   * @function mutationService             (value: Service | undefined) => void
   * @function mutationServiceList         (value: Service[] | undefined) => void
   * @function mutationServiceSettings     (value: ListSettings | undefined) => void
   * @function mutationQuery                (value: ServiceQuery | undefined) => void
   */
  const mutationService = (value: Service | undefined) => {
    service.value = value
  }
  const mutationServiceList = (value: Service[] | undefined) => {
    serviceList.value = value
  }
  const mutationServiceSettings = (value: ListSettings | undefined) => {
    serviceSettings.value = value
  }
  const mutationQuery = (value: ServiceQuery | undefined) => {
    query.value = value
  }

  const getService = async (payload: BaseGetPayload) => {
    const response = await api().services.get(payload)
    mutationService(response.data)
  }
  const searchServices = async (httpQuery: object) => {
    const response = await api().services.search(httpQuery)
    mutationServiceList(response.data.services)
    mutationServiceSettings(response.data.settings)
  }
  const createService = async (payload: ServiceCreatePayload) => {
    const response = await api().services.create(payload)
    mutationService(response.data)
  }
  const updateService = async (payload: ServiceUpdatePayload) => {
    const response = await api().services.update(payload)
    mutationService(response.data)
  }
  const deleteService = async (payload: BaseDeletePayload) => {
    await api().services.delete(payload)
    searchServices(payload)
    resetService()
  }

  const setQuery = (value: ServiceQuery | undefined) => {
    mutationQuery(value)
  }

  const resetService = () => {
    mutationService(undefined)
  }
  const resetServiceList = () => {
    mutationServiceList(undefined)
  }
  const resetServiceSettings = () => {
    mutationServiceSettings(undefined)
  }

  return {
    getterService,
    getterServiceList,
    getterServiceSettings,
    getterQuery,
    getService,
    searchServices,
    createService,
    updateService,
    deleteService,
    setQuery,
    resetService,
    resetServiceList,
    resetServiceSettings,
  }
})
