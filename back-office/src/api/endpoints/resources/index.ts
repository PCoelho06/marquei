import { builder } from '@/api/engine'
import type {
  ResourceGetPayload,
  ResourceCreatePayload,
  ResourceUpdatePayload,
} from '@/types/resources'

const resources = {
  get: async ({ payload, httpQuery }: { payload: ResourceGetPayload; httpQuery?: object }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'GET', payload, httpQuery }),
  search: async ({ httpQuery }: { httpQuery?: object }) =>
    await builder({ url: `/api/resources/`, method: 'GET', payload: {}, httpQuery }),
  create: async (payload: ResourceCreatePayload) =>
    await builder({ url: '/api/resources/', method: 'POST', payload }),
  update: async (payload: ResourceUpdatePayload) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...resources }
