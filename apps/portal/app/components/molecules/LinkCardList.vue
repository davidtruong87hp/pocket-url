<script setup lang="ts">
interface Link {
  id: string
  title: string
  shortCode: string
  originalUrl: string
  clicks: number
  createdAt: string
  tags?: string[]
  favicon?: string
}

interface Props {
  link: Link
  selected?: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'toggle-select': []
  edit: []
  delete: []
  'view-details': []
}>()

const showMenu = ref(false)

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  })
}
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors"
  >
    <div class="px-4 py-2">
      <div
        class="p-5 bg-white border border-gray-200 task rounded-xl shadow-theme-sm dark:border-gray-800 dark:bg-white/5"
      >
        <div
          class="w-full flex gap-5 xl:flex-row xl:items-center xl:justify-between"
        >
          <div class="flex items-start w-full gap-4">
            <div class="relative w-full flex items-center">
              <div class="w-full flex items-center gap-4">
                <!-- Checkbox -->
                <div>
                  <input
                    type="checkbox"
                    id="taskCheckbox1"
                    class="sr-only taskCheckbox"
                  />
                  <div
                    class="flex items-center justify-center h-5 max-w-5 border border-gray-300 rounded-md box dark:border-gray-700"
                  >
                    <span class="opacity-0">
                      <svg
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M11.6668 3.5L5.25016 9.91667L2.3335 7"
                          stroke="white"
                          stroke-width="1.94437"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </span>
                  </div>
                </div>

                <!-- Favicon/Icon -->
                <div class="shrink-0 pt-1">
                  <div
                    v-if="link.favicon"
                    class="w-10 h-10 rounded overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                  >
                    <img
                      :src="link.favicon"
                      :alt="link.title"
                      class="w-6 h-6"
                    />
                  </div>
                  <div
                    v-else
                    class="w-10 h-10 rounded bg-red-100 dark:bg-red-900/20 flex items-center justify-center"
                  >
                    <svg
                      class="w-6 h-6 text-red-600 dark:text-red-400"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                      />
                    </svg>
                  </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                  <!-- Title -->
                  <h3
                    class="text-base font-semibold text-gray-900 dark:text-white mb-2 hover:text-blue-600 dark:hover:text-blue-400 cursor-pointer"
                  >
                    {{ link.title }}
                  </h3>

                  <!-- Short URL & Original URL -->
                  <div class="space-y-1.5">
                    <!-- Short URL -->
                    <div class="flex items-center gap-2">
                      <a
                        :href="`https://bit.ly/${link.shortCode}`"
                        target="_blank"
                        class="text-blue-600 dark:text-blue-400 font-medium text-sm hover:underline"
                      >
                        pocket.url/{{ link.shortCode }}
                      </a>
                      <button
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        title="Copy to clipboard"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                          />
                        </svg>
                      </button>
                      <span class="text-gray-400 dark:text-gray-500">•</span>
                      <a
                        :href="link.originalUrl"
                        target="_blank"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 truncate"
                      >
                        {{ link.originalUrl }}
                      </a>
                    </div>
                  </div>

                  <!-- Meta Info -->
                  <div
                    class="flex items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400"
                  >
                    <!-- Click Data -->
                    <button
                      class="flex items-center gap-1.5 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                    >
                      <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                        />
                      </svg>
                      <span class="font-medium">Click data</span>
                    </button>

                    <!-- Created Date -->
                    <div class="flex items-center gap-1.5">
                      <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                      </svg>
                      <span>{{ formatDate(link.createdAt) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Actions Menu -->
                <div class="relative">
                  <button
                    @click="showMenu = !showMenu"
                    class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                      />
                    </svg>
                  </button>

                  <!-- Dropdown Menu -->
                  <Transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95"
                    enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95"
                  >
                    <div
                      v-if="showMenu"
                      v-click-away="() => (showMenu = false)"
                      class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10"
                    >
                      <button
                        class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-3"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                          />
                        </svg>
                        View link details
                      </button>

                      <button
                        class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-3"
                      >
                        <svg
                          class="w-4 h-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                          />
                        </svg>
                        Delete
                      </button>
                    </div>
                  </Transition>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
