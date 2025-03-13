import { builder } from '@/api/engine'
import type {
  SubscriptionCreatePayload,
  SubscriptionCancelPayload,
  SubscriptionSwitchPayload,
} from '@/types/subscriptions'

const subscriptions = {
  create: async (payload: SubscriptionCreatePayload) =>
    await builder({ url: '/api/subscriptions/', method: 'POST', payload }),
  get: async (payload: { id: number }) =>
    await builder({ url: `/api/salons/${payload.id}/subscription`, method: 'GET', payload }),
  switch: async (payload: SubscriptionSwitchPayload) =>
    await builder({ url: '/api/subscriptions/switch', method: 'PUT', payload }),
  cancel: async (payload: SubscriptionCancelPayload) =>
    await builder({
      url: `/api/subscriptions/${payload.subscriptionId}`,
      method: 'DELETE',
      payload,
    }),
}

export default { ...subscriptions }
