import { builder } from '@/api/engine'

const user = {
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/user/${payload.id}`, method: 'GET', payload }),
}

export default { ...user }
