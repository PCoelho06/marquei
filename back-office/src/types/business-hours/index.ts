interface TimeRange {
  start: string
  end: string
}

interface BusinessHoursRanges {
  day: number
  isOpen: boolean
  timeRanges: TimeRange[]
}

export interface BusinessHoursPayload {
  id: string
  businessHoursRanges: BusinessHoursRanges[]
}
