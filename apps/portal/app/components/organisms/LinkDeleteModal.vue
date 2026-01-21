<script setup lang="ts">
import type { Link } from '~/types'
import { useLinksStore } from '~/stores/links'

interface Props {
  link: Link
}

const props = defineProps<Props>()

const emit = defineEmits(['close'])

const { deleteLink, loading } = useLinksStore()

const handleDelete = async () => {
  const result = await deleteLink(props.link.short_url)
  useNotification().success({
    title: result ? 'Success' : 'Error',
    message: result ? 'Link deleted successfully' : 'Something went wrong',
  })

  emit('close')
  navigateTo('/links')
}
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
        <base-button
          variant="ghost"
          class="absolute right-5 top-5 z-999"
          @click="$emit('close')"
        >
          <Icon name="lucide:x" size="1.25rem" />
        </base-button>

        <form class="flex flex-col" @submit.prevent="handleDelete">
          <div class="overflow-y-auto px-2">
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2"></div>
          </div>
          <div class="flex gap-6 px-2 mt-6 justify-end">
            <div class="flex items-center w-full gap-3 sm:w-auto">
              <base-button @click="emit('close')" :disabled="loading"
                >Cancel</base-button
              >
              <base-button variant="danger" :disabled="loading"
                >Delete Link</base-button
              >
            </div>
          </div>
        </form>
      </div>
    </template>
  </modal>
</template>
