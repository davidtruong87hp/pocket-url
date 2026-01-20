<script setup lang="ts">
import { object, string } from 'yup'
import type { SubmissionContext } from 'vee-validate'

definePageMeta({
  layout: 'auth',
  middleware: 'sanctum:guest',
})

const { register } = useAuth()
const { handleServerErrors } = useFormServerErrors()

const schema = object({
  fname: string().required('First name is required'),
  lname: string().required('Last name is required'),
  email: string().required('Email is required').email('Email is invalid'),
  password: string()
    .required('Password is required')
    .min(8, 'Must be at least 8 characters'),
})

const loading = ref(false)
const generalError = ref('')

const handleRegister = async (values: any, ctx: SubmissionContext) => {
  loading.value = true
  generalError.value = ''

  try {
    await register({
      name: `${values.fname} ${values.lname}`,
      email: values.email,
      password: values.password,
      password_confirmation: values.password_confirmation,
    })
  } catch (error: any) {
    // Handle server validation errors
    const errorMessage = handleServerErrors(error, ctx)

    if (errorMessage) {
      useNotification().error({
        title: 'Error',
        message: errorMessage,
      })
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <div class="mb-5 sm:mb-8">
      <h1
        class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
      >
        Sign Up
      </h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Enter your email and password to sign up!
      </p>
    </div>
    <div>
      <VeeForm
        :validation-schema="schema"
        @submit="handleRegister"
        class="space-y-5"
        v-slot="{ errors }"
      >
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="sm:col-span-1">
            <base-input
              label="First Name"
              name="fname"
              placeholder="Enter your first name"
              :required="true"
            />
          </div>
          <div class="sm:col-span-1">
            <base-input
              label="Last Name"
              name="lname"
              placeholder="Enter your last name"
              :required="true"
            />
          </div>
        </div>
        <base-input
          type="email"
          label="Email"
          name="email"
          placeholder="Enter your email"
          autocomplete="email"
          :required="true"
        />

        <!-- Password -->
        <base-password-input
          label="Password"
          name="password"
          placeholder="Enter your password"
          hint="Must be at least 8 characters"
        />
        <base-password-input
          label="Password Confirmation"
          name="password_confirmation"
          placeholder="Enter your password confirmation"
        />

        <!-- Button -->
        <div>
          <base-button variant="primary" class="w-full" :disabled="loading">{{
            loading ? 'Submitting' : 'Sign Up'
          }}</base-button>
        </div>
      </VeeForm>
      <div class="mt-5">
        <p
          class="text-sm font-normal text-center text-gray-700 dark:text-gray-400 sm:text-start"
        >
          Already have an account?
          <nuxt-link
            href="/sign-in"
            class="text-brand-500 hover:text-brand-600 dark:text-brand-400"
            >Sign In</nuxt-link
          >
        </p>
      </div>
    </div>
  </div>
</template>
