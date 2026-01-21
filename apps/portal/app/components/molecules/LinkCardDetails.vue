<script setup lang="ts">
import type { Link } from '~/types'

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
            <Icon
              name="lucide:link"
              size="1.5rem"
              class="w-7 h-7 text-blue-600 dark:text-blue-400"
            />
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
            <base-button variant="ghost" @click="showMenu = !showMenu">
              <Icon name="lucide:more-vertical" size="1.25rem" />
            </base-button>

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
                  @click="emit('delete')"
                >
                  Delete
                </button>
              </div>
            </Transition>
          </div>

          <!-- Edit Button -->
          <base-button variant="ghost" @click="emit('edit')">
            <Icon name="lucide:edit" size="1.5rem" />
          </base-button>
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
            :href="`${link.short_url}`"
            target="_blank"
            class="text-lg font-medium text-blue-600 dark:text-blue-400 hover:underline"
          >
            {{ link.short_url }}
          </a>
          <copy-button :text="link.short_url" size="lg" />
        </div>
      </div>

      <!-- Original URL -->
      <div>
        <label
          class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-2"
        >
          <Icon name="lucide:link" />
          Original link
        </label>
        <a
          :href="link.original_url"
          target="_blank"
          class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 break-all"
        >
          {{ link.original_url }}
        </a>
      </div>

      <!-- Divider -->
      <div class="border-t border-gray-200 dark:border-gray-700"></div>

      <div>
        <div class="flex items-center justify-between text-sm">
          <div class="flex items-center gap-2 mb-3">
            <Icon name="lucide:tag" size="1.25rem" class="text-gray-400" />
            <span class="text-sm font-medium text-gray-900 dark:text-white">
              No tags
            </span>
          </div>
          <span class="text-gray-900 dark:text-white font-medium">
            {{ formatDateTime(link.created_at) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
