export type Employee = {
  id: number
  name: string
}

export type Machine = {
  id: number
  name: string
}

export interface CreateEmployeePayload {
  name: string
  type: 'employee'
  salonId: number
}

export interface CreateMachinePayload {
  name: string
  type: 'machine'
  salonId: number
}

export interface UpdateEmployeePayload extends CreateEmployeePayload {
  id: number
}

export interface UpdateMachinePayload extends CreateMachinePayload {
  id: number
}
