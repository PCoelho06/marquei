const validateAppointmentsData = (data: any) => {
  const errors: any = {}

  if (!data.startsAt) {
    errors.startDate = 'E obrigatório informar a data de início'
  } else if (new Date(data.startDate) < new Date()) {
    errors.startDate = 'A data de início não pode ser no passado'
  }

  if (!data.resourceId) {
    errors.resourceId = 'E obrigatório informar o recurso pretendido'
  }

  if (!data.clientId) {
    errors.clientId = 'E obrigatório escolher um cliente existente ou criar um novo cliente'
  }

  if (!data.serviceId) {
    errors.serviceId = 'E obrigatório informar o serviço pretendido'
  }

  return errors
}

export default { validateAppointmentsData }
