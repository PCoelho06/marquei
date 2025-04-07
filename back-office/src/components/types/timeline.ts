export interface TimeSlot {
  time: Date
  label: string
  isPassed: boolean
}

export interface Resource {
  id: number
  name: string
}

interface Client {
  id: number
  name: string
  phone: string
  email: string
  totalVisits: number
}

export interface Event {
  id: number
  resourceId: number
  client: Client
  title: string
  startTime: string
  endTime: string
  duration: number
}
