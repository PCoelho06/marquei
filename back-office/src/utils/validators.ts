const isEmail = (email: string) => {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

const validateEmail = (email: string) => {
  if (!email) {
    return 'E preciso informar o email'
  } else if (!isEmail(email)) {
    return 'Este email não é válido'
  }

  return undefined
}

export const validateUserLoginData = (data: { email: string; password: string }) => {
  const errors: { email?: string; password?: string } = {}

  const emailError = validateEmail(data.email)
  if (emailError) errors.email = emailError

  if (!data.password) {
    errors.password = 'Uma senha é necessária'
  }

  return errors
}

const validatePassword = (password: string) => {
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

export const validateUserRegistrationData = (data: {
  email: string
  password: string
  confirmPassword: string
}) => {
  const errors: { email?: string; password?: string; confirmPassword?: string } = {}

  const emailError = validateEmail(data.email)
  if (emailError) errors.email = emailError

  const passwordError = validatePassword(data.password)
  if (passwordError) errors.password = validatePassword(data.password)

  if (data.password !== data.confirmPassword) {
    errors.confirmPassword = 'As senhas não coincidem'
  }

  return errors
}
