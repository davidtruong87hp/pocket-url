<script setup lang="ts">
defineEmits(['close'])
</script>

<template>
  <modal full-screen-backdrop>
    <template #body>
      <div
        class="no-scrollbar relative w-full max-w-175 overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-11"
      >
        <div class="px-2">
          <h4
            class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90"
          >
            Delete link?
          </h4>
          <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
            This cannot be undone.
          </p>
        </div>
        <!-- close btn -->
        <button
          @click="$emit('close')"
          class="transition-color absolute right-5 top-5 z-999 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300 sm:h-11 sm:w-11"
        >
          <svg
            class="fill-current size-5 sm:size-6"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
              fill=""
            ></path>
          </svg>
        </button>

        <form class="flex flex-col">
          <div class="overflow-y-auto px-2">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2"></div>
          </div>
          <div class="flex gap-6 px-2 mt-6 justify-end">
            <div class="flex items-center w-full gap-3 sm:w-auto">
              <button
                @click="$emit('close')"
                type="button"
                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/3 sm:w-auto"
              >
                Cancel
              </button>
              <button
                type="button"
                class="flex w-full justify-center rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-error-600 sm:w-auto"
              >
                Delete Link
              </button>
            </div>
          </div>
        </form>
      </div>
    </template>
  </modal>
</template>
