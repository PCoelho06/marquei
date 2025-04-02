import type { Salon } from '../salons'
import type { Availability, BaseQuery } from '..'

export type ResourceType = 'employee' | 'machine'

export interface Resource {
  id: number
  name: string
  salon: Salon
  type: ResourceType
  resourceAvailabilities: Availability[]
  createdAt: string
  updatedAt: string
}

export interface ResourceQuery extends BaseQuery {
  salon?: number
  type?: ResourceType
  name?: string
}

export interface ResourceCreatePayload {
  name: string
  salon: number
  type: ResourceType
}

export interface ResourceUpdatePayload extends ResourceCreatePayload {
  id: number
}

export interface ResourceFilters {
  salon: number[]
  type: ResourceType[]
  name: string
}
