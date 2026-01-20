interface User {
  id: number
  name: string
  email: string
  avatar?: string
}

export const useAuth = () => {
  const { user, isAuthenticated, login, logout } = useSanctumAuth<User>()

  return {
    user,
    isAuthenticated,
    login,
    logout,
  }
}
