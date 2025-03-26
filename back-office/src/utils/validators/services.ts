import type { ServiceCreatePayload } from '@/types/services'

const validateServiceData = (data: ServiceCreatePayload) => {
  const errors = {
    name: '',
    description: '',
    duration: '',
    price: '',
  }

  if (!data.name) {
    errors.name = 'É obrigatório informar o nome do serviço'
  }

  if (data.description.length > 200) {
    errors.description = 'A descrição do serviço não pode ter mais de 200 caracteres'
  }

  if (!data.duration) {
    errors.duration = 'É obrigatório informar a duração do serviço'
  }

  if (!data.price) {
    errors.price = 'É obrigatório informar o preço do serviço'
  }

  return errors
}

export default { validateServiceData }
