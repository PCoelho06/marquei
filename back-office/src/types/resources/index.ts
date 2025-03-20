import type { Salon } from '../salons'

export type ResourceType = 'employee' | 'machine'

export interface Resource {
  id: number
  name: string
  salon: Salon
  type: ResourceType
  createdAt: string
  updatedAt: string
}

export interface ResourceQuery {
  salon?: number
  type?: ResourceType
  name?: string
}

export interface ResourceGetPayload {
  id: number
}

export interface ResourceCreatePayload {
  name: string
  salon: number
  type: ResourceType
}

export interface ResourceUpdatePayload extends ResourceCreatePayload {
  id: number
}

export interface ResourceDeletePayload {
  id: number
}

export interface ResourceFilters {
  salon: number[]
  type: ResourceType[]
  name: string
}
