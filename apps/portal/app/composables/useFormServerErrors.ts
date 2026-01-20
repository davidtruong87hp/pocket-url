import type { SubmissionContext } from 'vee-validate'

export const useFormServerErrors = () => {
  const handleServerErrors = (error: any, ctx?: SubmissionContext) => {
    console.error('Server error: ', error)

    const responseData = error.response?._data || error.data || {}
    const statusCode = error.response?.status || 500

    if (statusCode === 422) {
      const serverErrors = responseData.errors || []

      if (serverErrors && ctx) {
        const transformedErrors = transformLaravelErrors(serverErrors)

        ctx.setErrors(transformedErrors)
        return null
      }
    }

    switch (statusCode) {
      case 401:
        return responseData.message || 'Invalid credentials'
      case 429:
        return 'Too many requests. Please try again later.'
    }

    return responseData.message || error.message || 'Something went wrong'
  }

  const transformLaravelErrors = (errors: Record<string, string[]>) => {
    const transformed: Record<string, string> = {}

    for (const [field, messages] of Object.entries(errors)) {
      transformed[field] = Array.isArray(messages)
        ? (messages[0] ?? '')
        : (messages as string)
    }

    return transformed
  }

  return {
    handleServerErrors,
    transformLaravelErrors,
  }
}
