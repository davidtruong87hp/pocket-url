<script setup lang="ts">
definePageMeta({
  layout: false,
})
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-gray-900">
    <header class="border-b border-gray-200 dark:border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-2">
            <div
              class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center"
            >
              <Icon name="lucide:link" size="1.25rem" class="text-white" />
            </div>
            <span class="text-xl font-bold text-gray-900 dark:text-white"
              >PocketURL</span
            >
          </div>

          <!-- Sign In Button -->
          <NuxtLink to="/sign-in">
            <base-button variant="primary">Sign In</base-button>
          </NuxtLink>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <main
      class="min-h-screen flex items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center"
    >
      <div class="w-full">
        <h1
          class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6"
        >
          Shorten Your Links
        </h1>

        <p
          class="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto"
        >
          Simple, fast, and reliable URL shortener. Create short links and track
          clicks.
        </p>

        <!-- CTA Button -->
        <NuxtLink to="/sign-up">
          <base-button variant="primary" size="lg">
            Get Started Free
          </base-button>
        </NuxtLink>

        <!-- Simple Stats -->
        <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto mt-20">
          <div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              10M+
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Links Created
            </div>
          </div>
          <div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              500K+
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Users
            </div>
          </div>
          <div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
              99.9%
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
              Uptime
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
