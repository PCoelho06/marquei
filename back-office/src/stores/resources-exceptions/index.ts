import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type {
  ResourceException,
  ResourceExceptionCreatePayload,
  ResourceExceptionDeletePayload,
  ResourceExceptionGetPayload,
  ResourceExceptionListPayload,
  ResourceExceptionUpdatePayload,
  ResourceExceptionQuery,
} from '@/types/resource-exceptions'
import type { ListSettings } from '@/types'

export const useResourceExceptionsStore = defineStore('resourceExceptions', () => {
  /**
   * @constant resourceExceptions               <ResourceException | undefined>
   * @constant resourceExceptionsList           <ResourceException[] | undefined>
   * @constant resourceExceptionsSettings       <ListSettings | undefined>
   * @constant query                            <ResourceExceptionQuery | undefined>
   */
  const resourceExceptions = ref<ResourceException | undefined>()
  const resourceExceptionsList = ref<ResourceException[] | undefined>()
  const resourceExceptionsSettings = ref<ListSettings | undefined>()
  const query = ref<ResourceExceptionQuery | undefined>()

  /**
   * @constant getterResourceException         <ResourceException | undefined>
   * @constant getterResourceExceptionList     <ResourceException[] | undefined>
   * @constant getterResourceExceptionSettings <ListSettings | undefined>
   * @constant getterQuery                     <ResourceExceptionQuery | undefined>
   */
  const getterResourceException = computed<ResourceException | undefined>(
    () => resourceExceptions.value,
  )
  const getterResourceExceptionList = computed<ResourceException[] | undefined>(
    () => resourceExceptionsList.value,
  )
  const getterResourceExceptionSettings = computed<ListSettings | undefined>(
    () => resourceExceptionsSettings.value,
  )
  const getterQuery = computed<ResourceExceptionQuery | undefined>(() => query.value)

  /**
   * @function mutationResourceException             (value: ResourceException | undefined) => void
   * @function mutationResourceExceptionList         (value: ResourceException[] | undefined) => void
   * @function mutationResourceExceptionSettings     (value: ListSettings | undefined) => void
   * @function mutationQuery                         (value: ResourceExceptionQuery | undefined) => void
   */
  const mutationResourceException = (value: ResourceException | undefined) => {
    resourceExceptions.value = value
  }
  const mutationResourceExceptionList = (value: ResourceException[] | undefined) => {
    resourceExceptionsList.value = value
  }
  const mutationResourceExceptionSettings = (value: ListSettings | undefined) => {
    resourceExceptionsSettings.value = value
  }
  const mutationQuery = (value: ResourceExceptionQuery | undefined) => {
    query.value = value
  }

  const getResourceException = async (payload: ResourceExceptionGetPayload) => {
    const response = await api().resourceExceptions.get(payload)
    mutationResourceException(response.data)
  }
  const listResourceExceptions = async (payload: ResourceExceptionListPayload) => {
    const response = await api().resourceExceptions.list(payload)
    mutationResourceExceptionList(response.data.resourceExceptions)
    mutationResourceExceptionSettings(response.data.settings)
  }
  const createResourceException = async (payload: ResourceExceptionCreatePayload) => {
    const response = await api().resourceExceptions.create(payload)
    mutationResourceException(response.data)
  }
  const updateResourceException = async (payload: ResourceExceptionUpdatePayload) => {
    const response = await api().resourceExceptions.update(payload)
    mutationResourceException(response.data)
  }
  const deleteResourceException = async (payload: ResourceExceptionDeletePayload) => {
    await api().resourceExceptions.delete(payload)
    listResourceExceptions({ resourceId: payload.resourceId, httpQuery: {} })
    resetResourceException()
  }

  const setQuery = (payload: ResourceExceptionQuery) => {
    mutationQuery(payload)
  }

  const resetResourceException = () => {
    mutationResourceException(undefined)
  }
  const resetResourceExceptionList = () => {
    mutationResourceExceptionList(undefined)
  }
  const resetResourceExceptionSettings = () => {
    mutationResourceExceptionSettings(undefined)
  }

  return {
    getterResourceException,
    getterResourceExceptionList,
    getterResourceExceptionSettings,
    getterQuery,
    getResourceException,
    listResourceExceptions,
    createResourceException,
    updateResourceException,
    deleteResourceException,
    setQuery,
    resetResourceException,
    resetResourceExceptionList,
    resetResourceExceptionSettings,
  }
})
