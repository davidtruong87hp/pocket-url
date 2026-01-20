import type { User } from './user'

export interface SignInCredentials {
  email: string
  password: string
}

export interface SignUpPayload {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface AuthResponse {
  user: User
  token: string
}
