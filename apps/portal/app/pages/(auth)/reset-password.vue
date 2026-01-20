<script setup lang="ts">
definePageMeta({
  layout: 'auth',
})
</script>

<template>
  <div class="mb-5 sm:mb-8">
    <h1
      class="text-title-sm sm:text-title-md mb-2 font-semibold text-gray-800 dark:text-white/90"
    >
      Forgot Your Password?
    </h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">
      Enter the email address linked to your account, and we’ll send you a link
      to reset your password.
    </p>
  </div>
  <div>
    <form>
      <div class="space-y-5">
        <!-- Email -->
        <base-input
          type="email"
          label="Email"
          id="email"
          name="email"
          placeholder="Enter your email"
          :required="true"
        />

        <!-- Button -->
        <div>
          <base-button variant="primary" class="w-full"
            >Send Reset Link</base-button
          >
        </div>
      </div>
    </form>
    <div class="mt-5">
      <p
        class="text-center text-sm font-normal text-gray-700 sm:text-start dark:text-gray-400"
      >
        Wait, I remember my password...
        <nuxt-link
          href="sign-in"
          class="text-brand-500 hover:text-brand-600 dark:text-brand-400"
          >Click here</nuxt-link
        >
      </p>
    </div>
  </div>
</template>
