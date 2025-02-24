import { builder } from '@/api/engine'
import type { CreateEmployeePayload, UpdateEmployeePayload } from '@/types/resources'

const employees = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'GET', payload }),
  list: async () => await builder({ url: '/api/resources/employees', method: 'GET', payload: {} }),
  create: async (payload: CreateEmployeePayload) =>
    await builder({ url: '/api/resources', method: 'POST', payload }),
  update: async (payload: UpdateEmployeePayload) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'PUT', payload }),
  delete: async (payload: { id: number }) =>
    await builder({ url: `/api/resources/${payload.id}`, method: 'DELETE', payload }),
}

export default { ...employees }
