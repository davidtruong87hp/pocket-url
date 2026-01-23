<script setup lang="ts">
import type { ReferrerBreakdown } from '~/types'

interface Props {
  data: ReferrerBreakdown[]
  title: string
}
defineProps<Props>()
</script>

<template>
  <ChartCard :title="title">
    <BarChart :data="data" />
  </ChartCard>
</template>
