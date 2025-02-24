import { builder } from '@/api/engine'
import type { CreateMachinePayload, UpdateMachinePayload } from '@/types/resources'

const machines = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'GET', payload }),
  list: async () => await builder({ url: '/api/resources/machines', method: 'GET', payload: {} }),
  create: async (payload: CreateMachinePayload) =>
    await builder({ url: '/api/resources', method: 'POST', payload }),
  update: async (payload: UpdateMachinePayload) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...machines }
