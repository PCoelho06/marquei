import type { Payload } from '..'

export interface UserAuthPayload {
  email: string
  password: string
}

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

export type UserRegisterPayload = UserAuthPayload & {
  confirmPassword: string
}

export type UserRoles = 'ROLE_SUPER_ADMIN' | 'ROLE_ADMIN' | 'ROLE_OWNER' | 'ROLE_USER'
