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
          <!-- Email -->
          <div>
            <label
              class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
            >
              Email<span class="text-error-500">*</span>
            </label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="info@gmail.com"
              class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
            />
          </div>
          <!-- Password -->
          <div>
            <label
              class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
            >
              Password<span class="text-error-500">*</span>
            </label>
            <div x-data="{ showPassword: false }" class="relative">
              <input
                type="password"
                placeholder="Enter your password"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
              />
              <span
                class="absolute z-30 text-gray-500 -translate-y-1/2 cursor-pointer right-4 top-1/2 dark:text-gray-400"
              >
                <Icon name="lucide:eye" size="1rem" />
              </span>
            </div>
          </div>
          <!-- Checkbox -->
          <div class="flex items-center justify-between">
            <nuxt-link
              href="/reset-password"
              class="ml-auto text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400"
              >Forgot password?
            </nuxt-link>
          </div>
          <!-- Button -->
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
