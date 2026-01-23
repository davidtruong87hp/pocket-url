<script setup lang="ts">
import type { CardStat } from '~/types'
import StatCard from '../atoms/StatCard.vue'

interface Props {
  stats: CardStat[]
}

defineProps<Props>()
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <StatCard
      v-for="(stat, idx) in stats"
      :key="idx"
      :title="stat.title"
      :value="stat.value"
      :subtitle="stat.subtitle"
    />
  </div>
</template>
