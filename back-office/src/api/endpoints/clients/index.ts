import { builder } from '@/api/engine'
import type { BaseDeletePayload, BaseGetPayload } from '@/types'
import type { ClientCreatePayload, ClientUpdatePayload } from '@/types/clients'

const appointments = {
  get: async (payload: BaseGetPayload) =>
    await builder({ url: `/api/clients/${payload.id}`, method: 'GET', payload }),
  search: async ({ httpQuery }: { httpQuery?: object }) =>
    await builder({ url: `/api/clients/`, method: 'GET', payload: {}, httpQuery }),
  create: async (payload: ClientCreatePayload) =>
    await builder({ url: '/api/clients/', method: 'POST', payload }),
  update: async (payload: ClientUpdatePayload) =>
    await builder({ url: `/api/clients/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: BaseDeletePayload) =>
    await builder({ url: `/api/clients/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...appointments }
