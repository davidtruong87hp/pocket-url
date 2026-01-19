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
          <Icon name="lucide:x" size="1.25rem" />
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
