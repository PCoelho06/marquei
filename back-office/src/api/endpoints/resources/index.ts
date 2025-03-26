import { builder } from '@/api/engine'

import type { BaseDeletePayload, BaseGetPayload } from '@/types'
import type { ResourceCreatePayload, ResourceUpdatePayload } from '@/types/resources'

const resources = {
  get: async ({ payload, httpQuery }: { payload: BaseGetPayload; httpQuery?: object }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'GET', payload, httpQuery }),
  search: async ({ httpQuery }: { httpQuery?: object }) =>
    await builder({ url: `/api/resources/`, method: 'GET', payload: {}, httpQuery }),
  create: async (payload: ResourceCreatePayload) =>
    await builder({ url: '/api/resources/', method: 'POST', payload }),
  update: async (payload: ResourceUpdatePayload) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: BaseDeletePayload) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...resources }
