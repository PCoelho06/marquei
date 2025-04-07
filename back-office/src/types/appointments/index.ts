import type { Resource } from '../resources'

interface Client {
  id: number
  name: string
  phone: string
  email: string
  totalVisits: number
}

interface Service {
  id: number
  name: string
  price: number
}

export interface AppointmentCreatePayload {
  resourceId: number
  clientId: number
  serviceId: number
  startsAt: string
  endsAt: string
}

export interface AppointmentUpdatePayload extends AppointmentCreatePayload {
  id: number
}

export interface Appointment {
  id: number
  client: Client
  service: Service
  resource: Resource
  startsAt: string
  endsAt: string
  createdAt?: string
  updatedAt?: string
}
