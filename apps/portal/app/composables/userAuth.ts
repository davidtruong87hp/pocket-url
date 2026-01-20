interface User {
  id: number
  name: string
  email: string
  avatar?: string
}

interface RegisterPayload {
  name: string
  email: string
  password: string
  password_confirmation: string
}

interface RegisterResponse {
  token: string
  user: User
}

export const useAuth = () => {
  const { user, isAuthenticated, login, logout } = useSanctumAuth<User>()

  const register = async (payload: RegisterPayload) => {
    const response: RegisterResponse | null = await $fetch(
      '/gateway/api/register',
      {
        method: 'POST',
        body: payload,
      }
    )

    if (response?.token) {
      await login({
        email: payload.email,
        password: payload.password,
      })
    }
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
    register,
  }
}
