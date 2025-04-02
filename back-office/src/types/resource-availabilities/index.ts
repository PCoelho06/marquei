interface TimeRange {
  start: string
  end: string
}

export interface AvailabilityRanges {
  dayOfWeek: number
  isAvailable: boolean
  timeRanges: TimeRange[]
}

export interface resourceAvailabilitiesPayload {
  id: string
  availabilities: AvailabilityRanges[]
}
