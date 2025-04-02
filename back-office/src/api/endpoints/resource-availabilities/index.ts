import { builder } from '@/api/engine'
import type { resourceAvailabilitiesPayload } from '@/types/resource-availabilities'

const resourceAvailabilities = {
  manage: async (payload: resourceAvailabilitiesPayload) =>
    await builder({
      url: `/api/admin/resources_availability/${payload.id}`,
      method: 'POST',
      payload: payload.availabilities,
    }),
}

export default { ...resourceAvailabilities }
