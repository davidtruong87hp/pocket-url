<script setup lang="ts">
const links = ref([
  {
    id: '1',
    title: 'Link Title 1',
    shortCode: 'abc123',
    shortDomain: 'pocket.url',
    originalUrl: 'https://www.example.com',
    createdAt: '2022-01-01',
  },
  {
    id: '2',
    title: 'Link Title 2',
    shortCode: 'def123',
    shortDomain: 'pocket.url',
    originalUrl: 'https://www.example.com',
    createdAt: '2022-01-01',
  },
  {
    id: '3',
    title: 'Link Title 3',
    shortCode: 'ghi123',
    shortDomain: 'pocket.url',
    originalUrl: 'https://www.example.com',
    createdAt: '2022-01-01',
  },
])
</script>

<template>
  <div class="bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">
      <link-card-list v-for="link in links" :key="link.id" :link="link" />
    </div>

    <div class="px-6 py-4 dark:border-gray-800">
      <pagination />
    </div>
  </div>
</template>
