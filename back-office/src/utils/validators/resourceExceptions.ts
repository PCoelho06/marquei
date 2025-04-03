import type { ResourceExceptionCreatePayload } from '@/types/resource-exceptions'

const validateResourceExceptionsData = (data: ResourceExceptionCreatePayload) => {
  const errors = {
    date: '',
    startTime: '',
    endTime: '',
  }

  if (!data.date) {
    errors.date = 'A data da indisponibilidade é obrigatória'
  }
  if (new Date(data.date) < new Date()) {
    errors.date = 'A data da indisponibilidade não pode ser no passado'
  }

  if (!data.startTime) {
    errors.startTime = 'O horário de início da indisponibilidade é obrigatório'
  }
  if (!data.endTime) {
    errors.endTime = 'O horário de término da indisponibilidade é obrigatório'
  }
  if (data.startTime && data.endTime) {
    const startTime = new Date(`1970-01-01T${data.startTime}:00`)
    const endTime = new Date(`1970-01-01T${data.endTime}:00`)
    if (startTime >= endTime) {
      errors.endTime =
        'O horário de término da indisponibilidade deve ser maior que o horário de início'
    }
  }

  return errors
}
export default { validateResourceExceptionsData }
