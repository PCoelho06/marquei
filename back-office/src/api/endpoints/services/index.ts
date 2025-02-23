import { builder } from '@/api/engine'
import type { ServiceCreatePayload, ServiceUpdatePayload } from '@/types/services'

const services = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/services/${payload.id}`, method: 'GET', payload }),
  list: async () => await builder({ url: '/api/services', method: 'GET', payload: {} }),
  create: async (payload: ServiceCreatePayload) =>
    await builder({ url: '/api/services/', method: 'POST', payload }),
  update: async (payload: ServiceUpdatePayload) =>
    await builder({ url: `/api/services/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/services/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...services }
