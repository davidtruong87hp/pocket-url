<script setup lang="ts">
const route = useRoute()
const store = useLinksStore()

const { domain, url } = route.params
const { currentLink, currentLinkStats } = storeToRefs(store)

onMounted(() => {
  store.fetchLink(`${domain}/${url}`)
})

const isDeleteLinkModal = ref(false)
const isEditLinkModal = ref(false)
</script>

<template>
  <div
    class="rounded-2xl border border-gray-200 bg-white px-5 py-7 space-y-4 dark:border-gray-800 dark:bg-white/3 xl:px-10 xl:py-12"
  >
    <link-card-details
      v-if="currentLink"
      :link="currentLink"
      @delete="isDeleteLinkModal = true"
      @edit="isEditLinkModal = true"
    />
    <stats-grid v-if="currentLinkStats" :stats="currentLinkStats.stats" />
    <engagement-timeline
      v-if="currentLinkStats"
      title="Engagement over time"
      :data="currentLinkStats.timeline"
    />
    <referrer-breakdown
      v-if="currentLinkStats"
      title="Referrer Breakdown"
      :data="currentLinkStats.referrerBreakdown"
    />
    <device-breakdown
      v-if="currentLinkStats"
      title="Device Breakdown"
      :data="currentLinkStats.deviceBreakdown"
      :total="currentLinkStats.stats[0]?.value || 0"
    />
    <location-table
      v-if="currentLinkStats"
      title="Location Breakdown by Country"
      :data="currentLinkStats.locationBreakdown.countries"
      :columns="['#', 'Country', 'Engagements', '%']"
    />
    <location-table
      v-if="currentLinkStats"
      title="Location Breakdown by City"
      :data="currentLinkStats.locationBreakdown.cities"
      :columns="['#', 'City', 'Engagements', '%']"
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
