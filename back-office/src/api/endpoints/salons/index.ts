import { builder } from '@/api/engine'
import type { SalonCreatePayload } from '@/types/salons'

const salons = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/salons/${payload.id}`, method: 'GET', payload }),
  list: async () => await builder({ url: '/api/salons/', method: 'GET', payload: {} }),
  create: async (payload: SalonCreatePayload) =>
    await builder({ url: '/api/salons/', method: 'POST', payload }),
}

export default { ...salons }
