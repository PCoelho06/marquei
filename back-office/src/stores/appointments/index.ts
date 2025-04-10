import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

import { api } from '@/api'

import type {
  Appointment,
  AppointmentCreatePayload,
  AppointmentUpdatePayload,
} from '@/types/appointments'
import type { BaseDeletePayload, BaseGetPayload, ListSettings } from '@/types'

export const useAppointmentsStore = defineStore('appointments', () => {
  /**
   * @constant appointment               <Appointment | undefined>
   * @constant appointmentList           <Appointment[] | undefined>
   * @constant appointmentSettings       <ListSettings | undefined>
   */
  const appointment = ref<Appointment | undefined>()
  const appointmentList = ref<Appointment[] | undefined>()
  const appointmentSettings = ref<ListSettings | undefined>()

  /**
   * @constant getterAppointment         <Appointment | undefined>
   * @constant getterAppointmentList     <Appointment[] | undefined>
   * @constant getterAppointmentSettings <ListSettings | undefined>
   */
  const getterAppointment = computed<Appointment | undefined>(() => appointment.value)
  const getterAppointmentList = computed<Appointment[] | undefined>(() => appointmentList.value)
  const getterAppointmentSettings = computed<ListSettings | undefined>(
    () => appointmentSettings.value,
  )

  /**
   * @function mutationAppointment             (value: Appointment | undefined) => void
   * @function mutationAppointmentList         (value: Appointment[] | undefined) => void
   * @function mutationAppointmentSettings     (value: ListSettings | undefined) => void
   */
  const mutationAppointment = (newValue: Appointment | undefined) => {
    appointment.value = newValue
  }
  const mutationAppointmentList = (newValue: Appointment[] | undefined) => {
    appointmentList.value = newValue
  }
  const mutationAppointmentSettings = (newValue: ListSettings | undefined) => {
    appointmentSettings.value = newValue
  }

  const getAppointment = async (payload: BaseGetPayload) => {
    try {
      const response = await api().appointments.get(payload)
      mutationAppointment(response.data)
    } catch (error) {
      mutationAppointment(undefined)
    }
  }
  const searchAppointments = async (httpQuery: object) => {
    const response = await api().appointments.search(httpQuery)
    mutationAppointmentList(response.data.appointments)
    mutationAppointmentSettings(response.data.settings)
  }
  const createAppointment = async (payload: AppointmentCreatePayload) => {
    const response = await api().appointments.create(payload)
    mutationAppointment(response.data)
  }
  const updateAppointment = async (payload: AppointmentUpdatePayload) => {
    const response = await api().appointments.update(payload)
    mutationAppointment(response.data)
  }
  const deleteAppointment = async (payload: BaseDeletePayload) => {
    await api().appointments.delete(payload)
    searchAppointments(payload)
    resetAppointment()
  }

  const resetAppointment = () => {
    mutationAppointment(undefined)
  }
  const resetAppointments = () => {
    mutationAppointmentList(undefined)
  }
  const resetAppointmentSettings = () => {
    mutationAppointmentSettings(undefined)
  }

  return {
    getterAppointment,
    getterAppointmentList,
    getterAppointmentSettings,
    getAppointment,
    searchAppointments,
    createAppointment,
    updateAppointment,
    deleteAppointment,
    resetAppointment,
    resetAppointments,
    resetAppointmentSettings,
  }
})
