import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { Client, ClientQuery, ClientCreatePayload, ClientUpdatePayload } from '@/types/clients'
import type { BaseDeletePayload, BaseGetPayload, ListSettings } from '@/types'

export const useClientsStore = defineStore('clients', () => {
  /**
   * @constant client               <Client | undefined>
   * @constant clientList           <Client[] | undefined>
   * @constant clientSettings       <ListSettings | undefined>
   * @constant query                <ClientQuery | undefined>
   */
  const client = ref<Client | undefined>()
  const clientList = ref<Client[] | undefined>()
  const clientSettings = ref<ListSettings | undefined>()
  const query = ref<ClientQuery | undefined>()

  /**
   * @constant getterClient         <Client | undefined>
   * @constant getterClientList     <Client[] | undefined>
   * @constant getterClientSettings <ListSettings | undefined>
   * @constant getterQuery          <ClientQuery | undefined>
   */
  const getterClient = computed<Client | undefined>(() => client.value)
  const getterClientList = computed<Client[] | undefined>(() => clientList.value)
  const getterClientSettings = computed<ListSettings | undefined>(() => clientSettings.value)
  const getterQuery = computed<ClientQuery | undefined>(() => query.value)

  /**
   * @function mutationClient             (value: Client | undefined) => void
   * @function mutationClientList         (value: Client[] | undefined) => void
   * @function mutationClientSettings     (value: ListSettings | undefined) => void
   * @function mutationQuery              (value: ClientQuery | undefined) => void
   */
  const mutationClient = (newValue: Client | undefined) => {
    client.value = newValue
  }
  const mutationClientList = (newValue: Client[] | undefined) => {
    clientList.value = newValue
  }
  const mutationClientSettings = (newValue: ListSettings | undefined) => {
    clientSettings.value = newValue
  }
  const mutationQuery = (newValue: ClientQuery | undefined) => {
    query.value = newValue
  }

  const getClient = async (payload: BaseGetPayload) => {
    try {
      const response = await api().clients.get(payload)
      mutationClient(response.data)
    } catch (error) {
      mutationClient(undefined)
    }
  }
  const searchClients = async (httpQuery: object) => {
    const response = await api().clients.search(httpQuery)
    mutationClientList(response.data.clients)
    mutationClientSettings(response.data.settings)
  }
  const createClient = async (payload: ClientCreatePayload) => {
    const response = await api().clients.create(payload)
    mutationClient(response.data)
  }
  const updateClient = async (payload: ClientUpdatePayload) => {
    const response = await api().clients.update(payload)
    mutationClient(response.data)
  }
  const deleteClient = async (payload: BaseDeletePayload) => {
    await api().clients.delete(payload)
    searchClients(payload)
    resetClient()
  }

  const setQuery = (payload: ClientQuery) => {
    mutationQuery(payload)
  }

  const resetClient = () => {
    mutationClient(undefined)
  }
  const resetClients = () => {
    mutationClientList(undefined)
  }
  const resetClientSettings = () => {
    mutationClientSettings(undefined)
  }

  return {
    getterClient,
    getterClientList,
    getterClientSettings,
    getterQuery,
    getClient,
    searchClients,
    createClient,
    updateClient,
    deleteClient,
    setQuery,
    resetClient,
    resetClients,
    resetClientSettings,
  }
})
