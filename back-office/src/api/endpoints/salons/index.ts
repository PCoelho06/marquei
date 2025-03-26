import { builder } from '@/api/engine'
import type { SalonCreatePayload, SalonUpdatePayload } from '@/types/salons'

const salons = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/admin/salons/${payload.id}`, method: 'GET', payload }),
  search: async ({ httpQuery }: { httpQuery?: object }) =>
    await builder({ url: `/api/admin/salons/`, method: 'GET', payload: {}, httpQuery }),
  create: async (payload: SalonCreatePayload) =>
    await builder({ url: '/api/admin/salons/', method: 'POST', payload }),
  update: async (payload: SalonUpdatePayload) =>
    await builder({ url: `/api/admin/salons/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/admin/salons/${payload.id}`, method: 'DELETE', payload }),
  getData: async () => await builder({ url: `/api/admin/salons/data`, method: 'GET', payload: {} }),
}

export default { ...salons }
