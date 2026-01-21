<script setup lang="ts">
import type { Link } from '~/types'

interface Props {
  links: Link[]
}

defineProps<Props>()
</script>

<template>
  <div class="bg-white dark:border-gray-800 dark:bg-white/3">
    <div
      v-if="links.length"
      class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible"
    >
      <link-card-list v-for="link in links" :key="link.id" :link="link" />
    </div>

    <div v-else class="px-6 py-4 dark:border-gray-800">
      <p
        class="flex items-center justify-center text-sm text-gray-500 dark:text-gray-400"
      >
        You have no links yet
      </p>
    </div>

    <div v-if="links.length" class="px-6 py-4 dark:border-gray-800">
      <pagination />
    </div>
  </div>
</template>
