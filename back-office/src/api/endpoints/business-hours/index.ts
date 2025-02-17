import { builder } from '@/api/engine'
import type { BusinessHoursPayload } from '@/types/business-hours'

const businessHours = {
  create: async (payload: BusinessHoursPayload) =>
    await builder({
      url: `/api/business-hours/${payload.id}`,
      method: 'POST',
      payload: payload.businessHoursRanges,
    }),
}

export default { ...businessHours }
