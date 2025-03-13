import { validatePhone, validatePostalCode } from './base'

import type { SalonCreatePayload } from '@/types/salons'
import type { SalonGeneralInformationValidation } from '@/types/validators'

const validateSalonData = (data: SalonCreatePayload) => {
  const errors: SalonGeneralInformationValidation = {
    name: '',
    phone: '',
    address: '',
    postalCode: '',
    city: '',
    country: '',
  }

  if (!data.name) {
    errors.name = 'O nome é obrigatório'
  }

  const phoneError = validatePhone(data.phone)
  if (phoneError) errors.phone = phoneError

  if (!data.address) {
    errors.address = 'O endereço é obrigatório'
  }

  const postalCodeError = validatePostalCode(data.postalCode)
  if (postalCodeError) errors.postalCode = postalCodeError

  if (!data.city) {
    errors.city = 'A cidade é obrigatória'
  }

  if (!data.country) {
    errors.country = 'O país é obrigatório'
  }

  return errors
}

export default { validateSalonData }
