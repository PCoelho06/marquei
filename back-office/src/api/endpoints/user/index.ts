import type { UserAuthPayload, UserLoginPayload } from '@/types/user'

import { builder } from '@/api/engine'

const user = {
  login: async (payload: UserLoginPayload) =>
    await builder({ url: '/api/user/login', method: 'POST', payload }, true),
  register: async (payload: UserAuthPayload) =>
    await builder({ url: '/api/user/register', method: 'POST', payload }, true),
}

export default { ...user }
