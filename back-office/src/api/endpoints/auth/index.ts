import type { UserRegisterPayload, UserLoginPayload } from '@/types/user'

import { builder } from '@/api/engine'

const auth = {
  login: async (payload: UserLoginPayload) =>
    await builder({ url: '/api/auth/login', method: 'POST', payload }),
  register: async (payload: UserRegisterPayload) =>
    await builder({ url: '/api/auth/register', method: 'POST', payload }),
  logout: async () => await builder({ url: '/api/auth/logout', method: 'POST', payload: {} }),
  refresh: async () =>
    await builder({ url: '/api/auth/refresh-token', method: 'POST', payload: {} }),
  selectMode: async (payload: { mode: 'management' | 'store'; salonId?: number }) => {
    let url = `/api/auth/${payload.mode}`
    if (payload.salonId) url += `?salonId=${payload.salonId}`
    return await builder({
      url,
      method: 'GET',
      payload,
    })
  },
  fetchUser: async () => await builder({ url: '/api/auth/', method: 'GET', payload: {} }),
}

export default { ...auth }
