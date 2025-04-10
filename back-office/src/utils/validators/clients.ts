import { validatePhone, validateEmail } from './base'

const validateClientsData = (data: any) => {
  const errors: any = {}

  if (!data.firstName) {
    errors.firstName = 'E obrigatório informar o apelido do cliente'
  }

  if (!data.lastName) {
    errors.lastName = 'E obrigatório informar o nome do cliente'
  }

  const phoneError = validatePhone(data.phone)
  if (phoneError) errors.phone = phoneError

  const emailError = validateEmail(data.email)
  if (emailError) errors.email = emailError

  return errors
}

export default { validateClientsData }
