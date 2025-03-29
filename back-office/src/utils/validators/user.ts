import { validateEmail, validatePassword } from './base'

const validateUserLoginData = (data: { email: string; password: string }) => {
  const errors: { email?: string; password?: string } = {}

  const emailError = validateEmail(data.email)
  if (emailError) errors.email = emailError

  if (!data.password) {
    errors.password = 'Uma senha é necessária'
  }

  return errors
}

const validateUserRegistrationData = (data: {
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

const validateConfirmPasswordData = (password: string) => {
  const passwordError = !password ? 'Uma senha é necessária' : ''

  return passwordError ? { password: passwordError } : {}
}

export default { validateUserLoginData, validateUserRegistrationData, validateConfirmPasswordData }
