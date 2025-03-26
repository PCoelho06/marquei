import type { ResourceCreatePayload } from '@/types/resources'

const validateResourceData = (data: ResourceCreatePayload) => {
  const errors = {
    name: '',
    type: '',
    salon: '',
  }

  if (!data.name) {
    errors.name = 'O nome é obrigatório'
  }

  if (!data.type) {
    errors.type = 'O tipo é obrigatório'
  }

  if (!data.salon) {
    errors.salon = 'É obrigatório selecionar um salão'
  }

  return errors
}

export default { validateResourceData }
