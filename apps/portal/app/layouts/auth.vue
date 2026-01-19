<template>
  <div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
    <div
      class="relative flex flex-col justify-center w-full h-screen dark:bg-gray-900 sm:p-0 lg:flex-row"
    >
      <div class="flex flex-col flex-1 w-full lg:w-1/2">
        <div class="w-full max-w-md pt-5 mx-auto sm:py-10">
          <nuxt-link
            href="/"
            class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
          >
            <Icon name="lucide:chevron-left" size="1.25rem" />
            Back to home
          </nuxt-link>
        </div>
        <div
          class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto"
        >
          <slot />
        </div>
      </div>

      <div
        class="relative items-center hidden w-full h-full bg-brand-950 dark:bg-white/5 lg:grid lg:w-1/2"
      >
        <div class="flex items-center justify-center z-1">
          <!-- ===== Common Grid Shape Start ===== -->
          <div class="flex flex-col items-center max-w-xs">
            <nuxt-link href="/" class="block mb-4">
              <base-logo class="h-28" />
            </nuxt-link>
            <p class="text-center text-gray-400 dark:text-white/60">
              Free and Open Source URL Shortener
            </p>
          </div>
        </div>
      </div>
      <!-- Toggler -->
      <div class="fixed z-50 hidden bottom-6 right-6 sm:block">
        <theme-toggle />
      </div>
    </div>
  </div>
</template>
