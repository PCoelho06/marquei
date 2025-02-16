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
