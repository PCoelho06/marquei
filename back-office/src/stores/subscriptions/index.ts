import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type { Subscription } from '@/types/salons'
import type {
  SubscriptionCancelPayload,
  SubscriptionCreatePayload,
  SubscriptionSwitchPayload,
} from '@/types/subscriptions'

export const useSubscriptionsStore = defineStore('subscriptions', () => {
  const subscription = ref<Subscription | undefined>()
  const subscriptionId = ref<number | undefined>()
  const stripeSubscription = ref<any | undefined>()
  const stripeSubscriptionId = ref<string | undefined>()
  const clientSecret = ref<string | undefined>()

  const getterSubscription = computed<Subscription | undefined>(() => subscription.value)
  const getterSubscriptionId = computed<number | undefined>(() => subscriptionId.value)
  const getterStripeSubscription = computed<any | undefined>(() => stripeSubscription.value)
  const getterStripeSubscriptionId = computed<string | undefined>(() => stripeSubscriptionId.value)
  const getterClientSecret = computed<string | undefined>(() => clientSecret.value)

  const mutationSubscription = (newValue: Subscription | undefined) => {
    subscription.value = newValue
  }
  const mutationSubscriptionId = (newValue: number | undefined) => {
    subscriptionId.value = newValue
  }
  const mutationStripeSubscription = (newValue: any | undefined) => {
    stripeSubscription.value = newValue
  }
  const mutationStripeSubscriptionId = (newValue: string | undefined) => {
    stripeSubscriptionId.value = newValue
  }
  const mutationClientSecret = (newValue: string | undefined) => {
    clientSecret.value = newValue
  }

  const getSubscription = async (payload: { id: number }) => {
    try {
      const response = await api().subscriptions.get(payload)
      mutationSubscription(response.data.subscription)
      mutationStripeSubscription(response.data.stripeSubscription)
    } catch (error) {
      resetSubscription()
    }
  }
  const createSubscription = async (payload: SubscriptionCreatePayload) => {
    const response = await api().subscriptions.create(payload)
    mutationSubscriptionId(response.data.subscriptionId)
    mutationStripeSubscriptionId(response.data.stripeSubscriptionId)
    mutationClientSecret(response.data.clientSecret)
  }

  const switchSubscription = async (payload: SubscriptionSwitchPayload) => {
    const response = await api().subscriptions.switch(payload)
    console.log('🚀 ~ switchSubscription ~ response:', response)
    mutationSubscription(response.data.subscription)
    mutationStripeSubscription(response.data.stripeSubscription)
  }
  const cancelSubscription = async (payload: SubscriptionCancelPayload) => {
    await api().subscriptions.cancel(payload)
    resetSubscription()
    resetSubscriptionId()
    resetStripeSubscription()
    resetStripeSubscriptionId()
    resetClientSecret()
  }

  const resetSubscription = () => {
    mutationSubscription(undefined)
  }
  const resetSubscriptionId = () => {
    mutationSubscriptionId(undefined)
  }
  const resetStripeSubscription = () => {
    mutationStripeSubscription(undefined)
  }
  const resetStripeSubscriptionId = () => {
    mutationStripeSubscriptionId(undefined)
  }
  const resetClientSecret = () => {
    mutationClientSecret(undefined)
  }

  return {
    getterSubscription,
    getterSubscriptionId,
    getterStripeSubscription,
    getterStripeSubscriptionId,
    getterClientSecret,
    getSubscription,
    createSubscription,
    switchSubscription,
    cancelSubscription,
    resetSubscription,
    resetSubscriptionId,
    resetStripeSubscription,
    resetStripeSubscriptionId,
    resetClientSecret,
  }
})
