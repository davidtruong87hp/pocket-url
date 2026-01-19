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
            <svg
              class="stroke-current"
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
            >
              <path
                d="M12.7083 5L7.5 10.2083L12.7083 15.4167"
                stroke=""
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
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
          <include src="./partials/common-grid-shape.html"></include>
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
