import type { Payload } from '..'

export interface UserLoginForm {
  email: string
  password: string
}

export type UserRegisterPayload = UserLoginForm

export interface User {
  id: number | null
  email: string | null
  roles: string[] | null
  createdAt: string | null
  updatedAt: string | null
}

export interface UserGetPayload extends Payload {
  id: string
}

export interface UserLoginPayload {
  username: string
  password: string
}

export type UserRegisterForm = UserRegisterPayload & {
  confirmPassword: string
}

export type UserRoles =
  | 'ROLE_SUPER_ADMIN'
  | 'ROLE_ADMIN'
  | 'ROLE_OWNER'
  | 'ROLE_MANAGER'
  | 'ROLE_EMPLOYEE'
  | 'ROLE_USER'

export interface Permission {
  id: number
  name: string
  createdAt: string
  updatedAt: string
}
