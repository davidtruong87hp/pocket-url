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
              <base-input
                label="First Name"
                id="fname"
                name="fname"
                placeholder="Enter your first name"
                :required="true"
              />
            </div>
            <!-- Last Name -->
            <div class="sm:col-span-1">
              <base-input
                label="Last Name"
                id="lname"
                name="lname"
                placeholder="Enter your last name"
                :required="true"
              />
            </div>
          </div>
          <!-- Email -->
          <base-input
            type="email"
            label="Email"
            id="email"
            name="email"
            placeholder="Enter your email"
            :required="true"
          />

          <!-- Password -->
          <base-password-input
            label="Password"
            placeholder="Enter your password"
          />

          <!-- Button -->
          <div>
            <base-button variant="primary" class="w-full">Sign Up</base-button>
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
