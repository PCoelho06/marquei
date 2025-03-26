import type { BaseQuery } from '..'

export interface ServiceCreatePayload {
  name: string
  description: string
  duration: number
  price: number
  salonId: number
}

export interface ServiceUpdatePayload extends ServiceCreatePayload {
  id: number
}

export interface Service {
  id: number
  name: string
  description: string
  duration: number
  price: number
  salonId: number
}

export interface ServiceQuery extends BaseQuery {
  salonId: number
  name?: string
  minPrice?: number
  maxPrice?: number
  minDuration?: number
  maxDuration?: number
}
