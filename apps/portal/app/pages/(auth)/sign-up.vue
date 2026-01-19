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
        Sign Up
      </h1>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Enter your email and password to sign up!
      </p>
    </div>
    <div>
      <form>
        <div class="space-y-5">
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- First Name -->
            <div class="sm:col-span-1">
              <label
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
              >
                First Name<span class="text-error-500">*</span>
              </label>
              <input
                type="text"
                id="fname"
                name="fname"
                placeholder="Enter your first name"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
              />
            </div>
            <!-- Last Name -->
            <div class="sm:col-span-1">
              <label
                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
              >
                Last Name<span class="text-error-500">*</span>
              </label>
              <input
                type="text"
                id="lname"
                name="lname"
                placeholder="Enter your last name"
                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
              />
            </div>
          </div>
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
              placeholder="Enter your email"
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
                <svg
                  class="fill-current"
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z"
                    fill="#98A2B3"
                  />
                </svg>
              </span>
            </div>
          </div>

          <!-- Button -->
          <div>
            <button
              class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600"
            >
              Sign Up
            </button>
          </div>
        </div>
      </form>
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
