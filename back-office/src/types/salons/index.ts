export interface SalonCreatePayload {
  name: string
  phone: string
  address: string
  postalCode: string
  city: string
  country: string
}

export interface Salon extends SalonCreatePayload {
  id: number
  createdAt: string
  updatedAt: string
}

export interface SalonUpdatePayload extends SalonCreatePayload {
  id: number
}

export interface BusinessHours {
  id: number
  dayOfWeek: string
  startTime: string
  endTime: string
}

export interface Service {
  id: number
  name: string
  description: string
  duration: number
  price: number
  isEditing?: boolean
}

export interface Subscription {
  id: number
  status: string
  startDate: string
  endDate: string
  createdAt: string
  updatedAt: string
}
