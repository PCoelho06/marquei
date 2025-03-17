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

export interface CreateResourcePayload {
  name: string
  salon: number
  type: ResourceType
}

export interface UpdateResourcePayload extends CreateResourcePayload {
  id: number
}
