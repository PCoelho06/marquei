const isEmail = (email: string) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

export const validateEmail = (email: string) => {
  if (!email) {
    return 'E preciso informar o email'
  } else if (!isEmail(email)) {
    return 'Este email não é válido'
  }

  return undefined
}

export const validatePassword = (password: string) => {
  if (!password) {
    return 'Uma senha é necessária'
  } else if (password.length < 6) {
    return 'A senha deve ter pelo menos 6 caracteres'
  } else if (password.length > 20) {
    return 'A senha deve ter no máximo 20 caracteres'
  } else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/.test(password)) {
    return 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial'
  }

  return undefined
}

export const validatePostalCode = (postalCode: string) => {
  if (!postalCode) {
    return 'O código postal é obrigatório'
  } else if (!/^\d{4}-\d{3}$/.test(postalCode)) {
    return 'O código postal deve estar no formato XXXX-XXX'
  }

  return undefined
}

export const validatePhone = (phone: string) => {
  if (!phone) {
    return 'O telefone é obrigatório'
  } else if (!/^\+351 [0-9]{3} [0-9]{3} [0-9]{3}$/.test(phone)) {
    return 'O telefone não está no formato correto'
  }

  return undefined
}
