<script setup lang="ts">
definePageMeta({
  layout: 'auth',
})
</script>

<template>
  <div>
    <div class="mb-5 sm:mb-8">
      <h1
        class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md"
      >
        Sign In
      </h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Enter your email and password to sign in!
      </p>
    </div>
    <div>
      <form>
        <div class="space-y-5">
          <base-input
            type="email"
            name="email"
            label="Email"
            :required="true"
            placeholder="info@email.com"
          />
          <base-password-input
            label="Password"
            placeholder="Enter your password"
          />
          <div class="flex items-center justify-between">
            <nuxt-link
              href="/reset-password"
              class="ml-auto text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400"
              >Forgot password?
            </nuxt-link>
          </div>
          <div>
            <base-button variant="primary" class="w-full">Sign In</base-button>
          </div>
        </div>
      </form>
      <div class="mt-5">
        <p
          class="text-sm font-normal text-center text-gray-700 dark:text-gray-400 sm:text-start"
        >
          Don't have an account?
          <nuxt-link
            href="/sign-up"
            class="text-brand-500 hover:text-brand-600 dark:text-brand-400"
            >Sign Up
          </nuxt-link>
        </p>
      </div>
    </div>
  </div>
</template>
