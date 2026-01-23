<script setup lang="ts">
defineProps({
  title: String,
})
</script>

<template>
  <div
    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm"
  >
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
        {{ title }}
      </h3>
    </div>

    <div class="px-6 py-5 space-y-6">
      <slot />
    </div>
  </div>
</template>
