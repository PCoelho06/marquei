const validateAppointmentsData = (data: any) => {
  const errors: any = {}

  if (!data.startDate) {
    errors.startDate = 'E obrigatório informar a data de início'
  } else if (new Date(data.startDate) < new Date()) {
    errors.startDate = 'A data de início não pode ser no passado'
  }

  if (!data.endDate) {
    errors.endDate = 'E obrigatório informar a data de fim'
  } else if (new Date(data.endDate) <= new Date(data.startDate)) {
    errors.endDate = 'A data de fim não pode ser anterior ou igual à data de início'
  }

  if (!data.resourceId) {
    errors.title = 'E obrigatório informar o recurso'
  }

  if (!data.clientId) {
    errors.client = 'E obrigatório informar o cliente'
  }

  if (!data.serviceId) {
    errors.service = 'E obrigatório informar o serviço'
  }

  return errors
}

export default { validateAppointmentsData }
