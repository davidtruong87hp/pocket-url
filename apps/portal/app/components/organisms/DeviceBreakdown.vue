<script setup lang="ts">
import type { DeviceBreakdown } from '~/types'

interface Props {
  data: DeviceBreakdown[]
  title: string
  total: number | string
}

defineProps<Props>()
</script>

<template>
  <ChartCard :title="title">
    <DonutChart :data="data" :total="total" center-label="Engagements" />
  </ChartCard>
</template>
