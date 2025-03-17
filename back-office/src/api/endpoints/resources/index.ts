import { builder } from '@/api/engine'
import type { CreateResourcePayload, UpdateResourcePayload, ResourceType } from '@/types/resources'

const resources = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'GET', payload }),
  listAll: async () => await builder({ url: `/api/resources/`, method: 'GET', payload: {} }),
  listByType: async (payload: { type: ResourceType }) =>
    await builder({ url: `/api/resources/${payload.type}`, method: 'GET', payload: {} }),
  create: async (payload: CreateResourcePayload) =>
    await builder({ url: '/api/resources/', method: 'POST', payload }),
  update: async (payload: UpdateResourcePayload) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...resources }
