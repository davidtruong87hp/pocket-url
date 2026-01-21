<script setup lang="ts">
const route = useRoute()
const store = useLinksStore()

const { domain, url } = route.params
const { currentLink } = storeToRefs(store)

onMounted(() => {
  store.fetchLink(`${domain}/${url}`)
})

const isDeleteLinkModal = ref(false)
const isEditLinkModal = ref(false)
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/3 xl:px-10 xl:py-12"
  >
    <link-card-details
      v-if="currentLink"
      :link="currentLink"
      @delete="isDeleteLinkModal = true"
      @edit="isEditLinkModal = true"
    />
    <link-delete-modal
      v-if="isDeleteLinkModal && currentLink"
      :link="currentLink"
      @close="isDeleteLinkModal = false"
    />
    <link-edit-modal
      v-if="isEditLinkModal && currentLink"
      :link="currentLink"
      @close="isEditLinkModal = false"
    />
  </div>
</template>
