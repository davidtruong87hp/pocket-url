<script setup lang="ts">
const link = {
  id: '1',
  title: 'Link Title 1',
  shortCode: 'abc123',
  shortDomain: 'pocket.url',
  originalUrl: 'https://www.example.com',
  createdAt: '2022-01-01',
}

const isDeleteLinkModal = ref(false)
const isEditLinkModal = ref(false)
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/3 xl:px-10 xl:py-12"
  >
    <link-card-details
      :link="link"
      @delete="isDeleteLinkModal = true"
      @edit="isEditLinkModal = true"
    />
    <link-delete-modal
      v-if="isDeleteLinkModal"
      @close="isDeleteLinkModal = false"
    />
    <link-edit-modal
      v-if="isEditLinkModal"
      :link="link"
      @close="isEditLinkModal = false"
    />
  </div>
</template>
