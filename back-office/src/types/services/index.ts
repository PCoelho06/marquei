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
