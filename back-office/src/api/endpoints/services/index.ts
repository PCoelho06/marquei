import { builder } from '@/api/engine'

import type { BaseDeletePayload, BaseGetPayload } from '@/types'
import type { ServiceCreatePayload, ServiceUpdatePayload } from '@/types/services'

const services = {
  get: async (payload: BaseGetPayload) =>
    await builder({ url: `/api/services/${payload.id}`, method: 'GET', payload }),
  search: async ({ httpQuery }: { httpQuery?: object }) =>
    await builder({ url: '/api/services/', method: 'GET', payload: {}, httpQuery }),
  create: async (payload: ServiceCreatePayload) =>
    await builder({ url: '/api/services/', method: 'POST', payload }),
  update: async (payload: ServiceUpdatePayload) =>
    await builder({ url: `/api/services/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: BaseDeletePayload) =>
    await builder({ url: `/api/services/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...services }
