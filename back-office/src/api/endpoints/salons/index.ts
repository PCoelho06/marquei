import { builder } from '@/api/engine'
import type { SalonCreatePayload, SalonUpdatePayload } from '@/types/salons'

const salons = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/salons/${payload.id}`, method: 'GET', payload }),
  getBusinessHours: async (payload: { id: number }) =>
    await builder({ url: `/api/salons/${payload.id}/business-hours`, method: 'GET', payload }),
  list: async () => await builder({ url: '/api/salons/', method: 'GET', payload: {} }),
  create: async (payload: SalonCreatePayload) =>
    await builder({ url: '/api/salons/', method: 'POST', payload }),
  update: async (payload: SalonUpdatePayload) =>
    await builder({ url: `/api/salons/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/salons/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...salons }
