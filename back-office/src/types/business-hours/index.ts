interface TimeRange {
  start: string
  end: string
}

export interface BusinessHoursRanges {
  dayOfWeek: number
  isOpen: boolean
  timeRanges: TimeRange[]
}

export interface BusinessHour {
  daysOfWeek: number[]
  startTime: string
  endTime: string
}

export interface BusinessHoursPayload {
  id: string
  businessHoursRanges: BusinessHoursRanges[]
}
