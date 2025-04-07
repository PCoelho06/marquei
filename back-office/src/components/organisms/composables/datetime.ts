import type { Appointment, AppointmentUpdatePayload } from '@/types/appointments'
import { formatters } from '@/utils'

export const getTimeSlots = (date: Date, startTime: string, endTime: string, timeStep: number) => {
  const slots = []
  const [startHour, startMinute] = startTime.split(':').map(Number)
  const [endHour, endMinute] = endTime.split(':').map(Number)

  const start = new Date(date)
  start.setHours(startHour, startMinute, 0, 0)

  const end = new Date(date)
  end.setHours(endHour, endMinute, 0, 0)

  let current = new Date(start)

  while (current <= end) {
    slots.push(formatters.formatTimeSlot(new Date(current)))
    current.setMinutes(current.getMinutes() + timeStep)
  }

  return slots
}

const getHoursAndMinutes = (datetime: string) => {
  const date = new Date(datetime)

  return {
    hours: date.getHours(),
    minutes: date.getMinutes(),
  }
}

export const getAppointmentDuration = (appointment: Appointment | AppointmentUpdatePayload) => {
  const { hours: startHours, minutes: startMinutes } = getHoursAndMinutes(appointment.startsAt)
  const { hours: endHours, minutes: endMinutes } = getHoursAndMinutes(appointment.endsAt)

  const durationInMinutes = (endHours - startHours) * 60 + (endMinutes - startMinutes)

  return formatters.formatDuration(durationInMinutes)
}

export const getAppointmentTopAndHeight = (
  dayStartTime: string,
  appointment: Appointment,
  timeSlotHeight: number,
  timeStep: number,
) => {
  const [dayStartHours, dayStartMinutes] = dayStartTime.split(':').map(Number)
  const { hours: startHours, minutes: startMinutes } = getHoursAndMinutes(appointment.startsAt)
  const { hours: endHours, minutes: endMinutes } = getHoursAndMinutes(appointment.endsAt)

  const timeFromStartOfDay = (startHours - dayStartHours) * 60 + (startMinutes - dayStartMinutes)
  const timeFromStartOfAppointment = (endHours - startHours) * 60 + (endMinutes - startMinutes)

  const top = (timeFromStartOfDay / timeStep) * timeSlotHeight
  const height = (timeFromStartOfAppointment / timeStep) * timeSlotHeight

  return { top, height }
}
