<script setup lang="ts">
import type { Link } from '~/types'

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

const showActionsMenu = ref(false)

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
                      <Icon name="lucide:check" />
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
                    <Icon
                      name="lucide:link"
                      size="1.5rem"
                      class="text-red-600"
                    />
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
                        :href="link.short_url"
                        target="_blank"
                        class="text-blue-600 dark:text-blue-400 font-medium text-sm hover:underline"
                      >
                        {{ link.short_url }}
                      </a>
                      <copy-button :text="link.short_url" />
                    </div>

                    <!-- Original URL -->
                    <div class="flex items-center gap-2">
                      <Icon name="lucide:corner-down-right" size="1rem" />
                      <a
                        :href="link.original_url"
                        target="_blank"
                        class="text-gray-500 dark:text-gray-400 font-medium text-sm hover:underline"
                      >
                        {{ link.original_url }}
                      </a>
                    </div>
                  </div>

                  <!-- Meta Info -->
                  <div
                    class="flex items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400"
                  >
                    <!-- Click Data -->
                    <base-button
                      variant="ghost"
                      class="hover:text-gray-700 hover:bg-transparent dark:hover:text-gray-300"
                    >
                      <Icon name="lucide:eye" size="1rem" />
                      <span class="font-medium">Click data</span>
                    </base-button>

                    <!-- Created Date -->
                    <div class="flex items-center gap-1.5">
                      <Icon name="lucide:calendar" size="1rem" />
                      <span>{{ formatDate(link.created_at) }}</span>
                    </div>
                  </div>
                </div>

                <!-- Actions Menu -->
                <div class="relative">
                  <base-button
                    variant="ghost"
                    @click="showActionsMenu = !showActionsMenu"
                  >
                    <Icon name="lucide:ellipsis-vertical" size="1.25rem" />
                  </base-button>

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
                      v-if="showActionsMenu"
                      class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10"
                    >
                      <nuxt-link
                        :to="`/links/${link.short_url}/details`"
                        class="w-full px-4 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-3"
                      >
                        <Icon name="lucide:link" size="1rem" />
                        View link details
                      </nuxt-link>

                      <button
                        class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-3"
                      >
                        <Icon name="lucide:trash-2" size="1rem" />
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
