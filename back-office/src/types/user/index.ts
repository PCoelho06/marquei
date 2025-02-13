import type { Payload } from '..'

export interface UserAuthPayload {
  email: string
  password: string
}

export interface User {
  id: number | null
  email: string | null
  firstname: string | null
  lastname: string | null
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
