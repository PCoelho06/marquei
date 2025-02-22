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
