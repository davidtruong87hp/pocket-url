<script setup lang="ts">
interface Link {
  id: string
  title: string
  shortCode: string
  shortDomain: string
  originalUrl: string
  createdAt: string
  tags?: string[]
  favicon?: string
}

interface Props {
  link?: Link
}

defineProps<Props>()

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
            {{ link?.id ? 'Edit Link' : 'Create a new link' }}
          </h4>
          <p
            v-if="!link"
            class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7"
          >
            You have 2 links and 3 custom back-halves remaining this month.
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
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <label
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                >
                  Destination URL
                </label>
                <input
                  type="text"
                  :value="link?.originalUrl"
                  placeholder="https://example.com/my-long-url"
                  class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                />
              </div>

              <div class="sm:col-span-2">
                <label
                  class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400"
                >
                  Title (optional)
                </label>
                <input
                  type="text"
                  :value="link?.title"
                  class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pl-4 pr-11 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                />
              </div>
            </div>
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
                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto"
              >
                Save
              </button>
            </div>
          </div>
        </form>
      </div>
    </template>
  </modal>
</template>
