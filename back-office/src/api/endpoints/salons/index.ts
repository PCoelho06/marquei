import { builder } from '@/api/engine'
import type { SalonCreatePayload } from '@/types/salons'

const salons = {
  create: async (payload: SalonCreatePayload) =>
    await builder({ url: '/api/salons/', method: 'POST', payload }),
}

export default { ...salons }
