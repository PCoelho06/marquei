import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type {
  Resource,
  ResourceQuery,
  ResourceCreatePayload,
  ResourceUpdatePayload,
} from '@/types/resources'
import type { BaseDeletePayload, BaseGetPayload, ListSettings } from '@/types'

export const useResourcesStore = defineStore('resources', () => {
  /**
   * @constant resource               <Resource | undefined>
   * @constant resourceList           <Resource[] | undefined>
   * @constant resourceSettings       <ListSettings | undefined>
   * @constant query                  <ResourceQuery | undefined>
   */
  const resource = ref<Resource | undefined>()
  const resourceList = ref<Resource[] | undefined>()
  const resourceSettings = ref<ListSettings | undefined>()
  const query = ref<ResourceQuery | undefined>()

  /**
   * @constant getterResource         <Resource | undefined>
   * @constant getterResourceList     <Resource[] | undefined>
   * @constant getterResourceSettings <ListSettings | undefined>
   * @constant getterQuery            <ResourceQuery | undefined>
   */
  const getterResource = computed<Resource | undefined>(() => resource.value)
  const getterResourceList = computed<Resource[] | undefined>(() => resourceList.value)
  const getterResourceSettings = computed<ListSettings | undefined>(() => resourceSettings.value)
  const getterQuery = computed<ResourceQuery | undefined>(() => query.value)

  /**
   * @function mutationResource             (value: Resource | undefined) => void
   * @function mutationResourceList         (value: Resource[] | undefined) => void
   * @function mutationResourceSettings     (value: ListSettings | undefined) => void
   * @function mutationQuery                (value: ResourceQuery | undefined) => void
   */
  const mutationResource = (value: Resource | undefined) => {
    resource.value = value
  }
  const mutationResourceList = (value: Resource[] | undefined) => {
    resourceList.value = value
  }
  const mutationResourceSettings = (value: ListSettings | undefined) => {
    resourceSettings.value = value
  }
  const mutationQuery = (value: ResourceQuery | undefined) => {
    query.value = value
  }

  const getResource = async (payload: BaseGetPayload) => {
    const response = await api().resources.get({ payload })
    mutationResource(response.data)
  }
  const searchResources = async (httpQuery: object) => {
    const response = await api().resources.search(httpQuery)
    mutationResourceList(response.data.resources)
    mutationResourceSettings(response.data.settings)
  }
  const createResource = async (payload: ResourceCreatePayload) => {
    const response = await api().resources.create(payload)
    mutationResource(response.data)
  }
  const updateResource = async (payload: ResourceUpdatePayload) => {
    const response = await api().resources.update(payload)
    mutationResource(response.data)
  }
  const deleteResource = async (payload: BaseDeletePayload) => {
    await api().resources.delete(payload)
    searchResources(payload)
    resetResource()
  }

  const setQuery = (value: ResourceQuery | undefined) => {
    mutationQuery(value)
  }

  const resetResource = () => {
    mutationResource(undefined)
  }
  const resetResourceList = () => {
    mutationResourceList(undefined)
  }
  const resetResourceSettings = () => {
    mutationResourceSettings(undefined)
  }

  return {
    getterResource,
    getterResourceList,
    getterResourceSettings,
    getterQuery,
    getResource,
    searchResources,
    createResource,
    updateResource,
    deleteResource,
    setQuery,
    resetResource,
    resetResourceList,
    resetResourceSettings,
  }
})
