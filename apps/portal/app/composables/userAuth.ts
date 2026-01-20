import type {
  AuthResponse,
  SignInCredentials,
  SignUpPayload,
  User,
} from '~/types'

export const useAuth = () => {
  const { user, isAuthenticated, login, logout } = useSanctumAuth<User>()

  const signIn = async (payload: SignInCredentials) => {
    await login({
      email: payload.email,
      password: payload.password,
    })
  }

  const signUp = async (payload: SignUpPayload) => {
    const response: AuthResponse | null = await $fetch(
      '/gateway/api/register',
      {
        method: 'POST',
        body: payload,
      }
    )

    if (response?.token) {
      await signIn({
        email: payload.email,
        password: payload.password,
      })
    }
  }

  return {
    user,
    isAuthenticated,
    signIn,
    logout,
    signUp,
  }
}
