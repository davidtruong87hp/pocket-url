<script setup lang="ts">
import type { Link } from '~/types'
import { useLinksStore } from '~/stores/links'

interface Props {
  links: Link[]
  onDeleteLink: (linkId: number) => void
  onChangePage: (page: number) => void
}

defineProps<Props>()

const store = useLinksStore()
</script>

<template>
  <div class="bg-white dark:border-gray-800 dark:bg-white/3">
    <div
      v-if="links.length"
      class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible"
    >
      <link-card-list
        v-for="link in links"
        :key="link.id"
        :link="link"
        @delete-link="onDeleteLink"
      />
    </div>

    <div v-else class="px-6 py-4 dark:border-gray-800">
      <p
        class="flex items-center justify-center text-sm text-gray-500 dark:text-gray-400"
      >
        You have no links yet
      </p>
    </div>

    <div v-if="links.length" class="px-6 py-4 dark:border-gray-800">
      <pagination
        :current-page="store.pagination.current_page"
        :total-pages="store.pagination.last_page"
        @change="onChangePage"
      />
    </div>
  </div>
</template>
