export interface SubscriptionCreatePayload {
  priceId: string
  salonId: string
}

export interface SubscriptionCancelPayload {
  subscriptionId: string
  stripeSubscriptionId: string
}

// export interface SubscriptionSwitchPayload {
//   priceId: string
//   salonId: string
//   subscriptionId: string
//   stripeSubscriptionId: string
// }

export interface SubscriptionSwitchPayload {
  priceId: string
  stripeSubscriptionId: string
  stripeItemId: string
}

export interface PricingPlan {
  name: string
  description: string
  includedFeatures: string[]
  notIncludedFeatures?: string[]
  pricing: {
    monthly: number
    yearly: number
    yearlyNoDiscount: number
  }
  priceIds: {
    monthly: string
    yearly: string
  }
  isActualPlan?: boolean
}

export interface SelectedPlan {
  name: string
  price_id: string
  price: number
  interval: 'monthly' | 'yearly'
}
