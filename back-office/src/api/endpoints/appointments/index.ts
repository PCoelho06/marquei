import { builder } from '@/api/engine'
import type { BaseDeletePayload, BaseGetPayload } from '@/types'
import type { AppointmentCreatePayload, AppointmentUpdatePayload } from '@/types/appointments'

const appointments = {
  get: async (payload: BaseGetPayload) =>
    await builder({ url: `/api/appointments/${payload.id}`, method: 'GET', payload }),
  search: async ({ httpQuery }: { httpQuery?: object }) =>
    await builder({ url: `/api/appointments/`, method: 'GET', payload: {}, httpQuery }),
  create: async (payload: AppointmentCreatePayload) =>
    await builder({ url: '/api/appointments/', method: 'POST', payload }),
  update: async (payload: AppointmentUpdatePayload) =>
    await builder({ url: `/api/appointments/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: BaseDeletePayload) =>
    await builder({ url: `/api/appointments/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...appointments }
