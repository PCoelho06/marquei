import { builder } from '@/api/engine'
import type { BaseDeletePayload, BaseGetPayload } from '@/types'
import type {
  ResourceExceptionCreatePayload,
  ResourceExceptionUpdatePayload,
  ResourceExceptionListPayload,
  ResourceExceptionGetPayload,
  ResourceExceptionDeletePayload,
} from '@/types/resource-exceptions'

const resourceExceptions = {
  get: async (payload: ResourceExceptionGetPayload) =>
    await builder({
      url: `/api/admin/${payload.resourceId}/resources_exception/${payload.id}`,
      method: 'GET',
      payload,
    }),
  list: async (payload: ResourceExceptionListPayload) =>
    await builder({
      url: `/api/admin/${payload.resourceId}/resources_exception/`,
      method: 'GET',
      payload: {},
      httpQuery: payload.httpQuery,
    }),
  create: async (payload: ResourceExceptionCreatePayload) =>
    await builder({
      url: `/api/admin/${payload.resourceId}/resources_exception/`,
      method: 'POST',
      payload,
    }),
  update: async (payload: ResourceExceptionUpdatePayload) =>
    await builder({
      url: `/api/admin/${payload.resourceId}/resources_exception/${payload.id}`,
      method: 'PUT',
      payload,
    }),
  delete: async (payload: ResourceExceptionDeletePayload) =>
    await builder({
      url: `/api/admin/${payload.resourceId}/resources_exception/${payload.id}`,
      method: 'DELETE',
      payload,
    }),
  getData: async () => await builder({ url: `/api/admin/salons/data`, method: 'GET', payload: {} }),
}

export default { ...resourceExceptions }
