import type { BaseQuery } from '..'

export interface ClientCreatePayload {
  firstName: string
  lastName: string
  email: string
  phone: string
}

export interface ClientUpdatePayload extends ClientCreatePayload {
  id: number
}

export interface Client extends ClientUpdatePayload {
  createdAt: string
  updatedAt: string
}

export interface ClientFilters {
  name: string
  phone: string
}

export interface ClientQuery extends BaseQuery {
  name?: string
  phone?: string
}
