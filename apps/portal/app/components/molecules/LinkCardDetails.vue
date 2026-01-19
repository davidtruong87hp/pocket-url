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
  link: Link
}

const props = defineProps<Props>()

const emit = defineEmits<{
  edit: []
  delete: []
}>()

const showMenu = ref(false)

const formatDateTime = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true,
    timeZoneName: 'short',
  })
}
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm"
  >
    <!-- Header -->
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-start gap-4">
        <!-- Favicon -->
        <div class="shrink-0">
          <div
            v-if="link.favicon"
            class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
          >
            <img :src="link.favicon" :alt="link.title" class="w-8 h-8" />
          </div>
          <div
            v-else
            class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center"
          >
            <svg
              class="w-7 h-7 text-blue-600 dark:text-blue-400"
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

        <!-- Title & Actions -->
        <div class="flex-1 min-w-0">
          <h1
            class="text-2xl font-bold text-gray-900 dark:text-white mb-1 wrap-break-word"
          >
            {{ link.title }}
          </h1>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2 shrink-0">
          <!-- More Menu -->
          <div class="relative">
            <button
              @click="showMenu = !showMenu"
              class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
              title="More options"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                />
              </svg>
            </button>

            <!-- Dropdown -->
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
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10"
              >
                <button
                  class="w-full px-4 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"
                  @click="$emit('delete')"
                >
                  Delete
                </button>
              </div>
            </Transition>
          </div>

          <!-- Edit Button -->
          <button
            @click="emit('edit')"
            class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors flex items-center gap-2"
            title="Edit"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="px-6 py-5 space-y-6">
      <!-- Short URL -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label class="text-sm font-medium text-gray-500 dark:text-gray-400">
            Short link
          </label>
        </div>
        <div class="flex items-center gap-3">
          <a
            :href="`https://${link.shortDomain}/${link.shortCode}`"
            target="_blank"
            class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline"
          >
            {{ link.shortDomain }}/{{ link.shortCode }}
          </a>
          <button
            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
            title="Copy to clipboard"
          >
            <svg
              class="w-5 h-5"
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
        </div>
      </div>

      <!-- Original URL -->
      <div>
        <label
          class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2"
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
          Original link
        </label>
        <a
          :href="link.originalUrl"
          target="_blank"
          class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 break-all"
        >
          {{ link.originalUrl }}
        </a>
      </div>

      <!-- Divider -->
      <div class="border-t border-gray-200 dark:border-gray-700"></div>

      <div>
        <div class="flex items-center justify-between text-sm">
          <div class="flex items-center gap-2 mb-3">
            <svg
              class="w-5 h-5 text-gray-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
              />
            </svg>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              No tags
            </span>
          </div>
          <span class="text-gray-900 dark:text-white font-medium">
            {{ formatDateTime(link.createdAt) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
